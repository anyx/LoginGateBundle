<?php

namespace Anyx\LoginGateBundle\Tests;

use Staffim\RestClient\Client as RestClient;
use Symfony\Bundle\FrameworkBundle\Console\Application;
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

    public function testCorrectLogin()
    {
        $peter = $this->getReference('user.peter');

        $response = $this->attemptLogin($peter->getEmail(), 'password');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCatchBruteForceAttempt()
    {
        $peter = $this->getReference('user.peter');

        for ($i = 0; $i < 3; ++$i) {
            $response = $this->attemptLogin($peter->getEmail(), 'wrong password', 401);
            $this->assertEquals('Invalid credentials.', $response->getData()['error']);
        }

        $response = $this->attemptLogin($peter->getEmail(), 'wrong password', 403);
        $this->assertEquals('Too many login attempts', $response->getData()['error']);
    }

    public function testClearLoginAttempts()
    {
        $httpClient = $this->createRestClient('');

        $httpClient->get('');

        /** @var \Anyx\LoginGateBundle\Service\BruteForceChecker $bruteForceChecker */
        $bruteForceChecker = static::$container->get('anyx.login_gate.brute_force_checker');
        $request = $httpClient->getKernelBrowser()->getRequest();

        $this->assertEquals(0, $bruteForceChecker->getStorage()->getCountAttempts($request));

        $peter = $this->getReference('user.peter');
        $this->attemptLogin($peter->getEmail(), 'wrong password', 401);
        $this->assertEquals(1, $bruteForceChecker->getStorage()->getCountAttempts($request));

        $this->attemptLogin($peter->getEmail(), 'password');
        $this->assertEquals(0, $bruteForceChecker->getStorage()->getCountAttempts($request));
    }

    /**
     * @return \Staffim\RestClient\Response
     */
    protected function attemptLogin(string $username, string $password, int $status = 200)
    {
        $httpClient = $this->createRestClient('/login');

        $loginData = ['username' => $username, 'password' => $password];

        return $httpClient->post($loginData, $status);
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
