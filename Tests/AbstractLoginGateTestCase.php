<?php

namespace Anyx\LoginGateBundle\Tests;

use Staffim\RestClient\Client as RestClient;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractLoginGateTestCase extends KernelTestCase
{
    abstract protected function loadFixtures(KernelInterface $kernel);

    /**
     * @var \Doctrine\Common\DataFixtures\ReferenceRepository
     */
    protected static $referenceRepository;

    public function setUp(): void
    {
        static::bootKernel();
        $this->loadFixtures(static::$kernel);
    }

    public function testCorrectJsonLogin()
    {
        $peter = $this->getReference('user.peter');

        $response = $this->attemptJsonLogin($peter->getEmail(), 'password');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCorrectFormLogin()
    {
        $peter = $this->getReference('user.peter');

        $response = $this->attemptFormLogin($peter->getEmail(), 'password');
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertTrue($response->isSuccessful());
        $this->assertStringContainsString($peter->getEmail(), $response->getContent());
    }

    public function testCatchBruteForceAttemptInJson()
    {
        $peter = $this->getReference('user.peter');

        for ($i = 0; $i < 3; ++$i) {
            $response = $this->attemptJsonLogin($peter->getEmail(), 'wrong password', 401);
            $this->assertEquals('Invalid credentials.', $response->getData()['error']);
        }

        $response = $this->attemptJsonLogin($peter->getEmail(), 'wrong password', 403);
        $this->assertEquals('Too many login attempts', $response->getData()['error']);
    }

    public function testCatchBruteForceAttemptInForm()
    {
        $peter = $this->getReference('user.peter');

        for ($i = 0; $i < 2; ++$i) {
            $response = $this->attemptFormLogin($peter->getEmail(), 'wrong password', 401);
            $this->assertStringContainsString('Invalid credentials.', $response->getContent());
        }

        $response = $this->attemptFormLogin($peter->getEmail(), 'wrong password', 401);
        $this->assertEquals('Too many login attempts', $response->getContent());
    }

    public function testClearLoginAttempts()
    {
        $httpClient = $this->createRestClient('');
        $httpClient->get('web');

        /** @var \Anyx\LoginGateBundle\Service\BruteForceChecker $bruteForceChecker */
        $bruteForceChecker = static::$container->get('anyx.login_gate.brute_force_checker');
        $request = $httpClient->getKernelBrowser()->getRequest();

        $this->assertEquals(0, $bruteForceChecker->getStorage()->getCountAttempts($request));

        $peter = $this->getReference('user.peter');
        $this->attemptJsonLogin($peter->getEmail(), 'wrong password', 401);
        $this->assertEquals(1, $bruteForceChecker->getStorage()->getCountAttempts($request));

        $this->attemptJsonLogin($peter->getEmail(), 'password');
        $this->assertEquals(0, $bruteForceChecker->getStorage()->getCountAttempts($request));
    }

    /**
     * @return \Staffim\RestClient\Response
     */
    protected function attemptJsonLogin(string $username, string $password, int $status = 200)
    {
        $httpClient = $this->createRestClient('/api/login');

        $loginData = ['username' => $username, 'password' => $password];

        return $httpClient->post($loginData, $status);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    protected function attemptFormLogin(string $username, string $password, int $status = 200)
    {
        /** @var $client KernelBrowser */
        $client = $this->getContainer()->get('test.client');
        $client->followRedirects();

        $crawler = $client->request('GET', '/web/login');
        $form = $crawler->selectButton('Sign in')->form();

        $form['email'] = $username;
        $form['password'] = $password;

        $client->submit($form);

        return $client->getResponse();
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    protected function getReference($name)
    {
        return $this->getReferenceRepository()->getReference($name);
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Container
     */
    protected function getContainer()
    {
        return static::$container;
    }

    /**
     * @return \Doctrine\Common\DataFixtures\ReferenceRepository
     */
    protected function getReferenceRepository()
    {
        return self::$referenceRepository;
    }

    protected static function executeCommand(string $command, array $options = [])
    {
        $application = new Application(static::$kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array_merge(
            [
                'command' => $command,
            ],
            $options
        ));

        $output = new BufferedOutput();
        $application->run($input, $output);

        return $output->fetch();
    }

    protected function createRestClient(string $url, array $headers = [])
    {
        return new RestClient(
            self::$kernel,
            $url,
            array_merge(['CONTENT_TYPE' => 'application/json'], $headers)
        );
    }
}
