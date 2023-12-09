<?php

namespace Anyx\LoginGateBundle\Tests;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;

abstract class AbstractLoginGateTestCase extends KernelTestCase
{
    use HasBrowser;

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

        $response->assertStatus(200);
    }

    public function testCorrectFormLogin()
    {
        $peter = $this->getReference('user.peter');

        $this->attemptFormLogin($peter->getEmail(), 'password')->assertSuccessful();
    }

    public function testCatchBruteForceAttemptInJson()
    {
        $peter = $this->getReference('user.peter');

        for ($i = 0; $i < 3; ++$i) {
            $this
                ->attemptJsonLogin($peter->getEmail(), 'wrong password', 401)
                ->assertJsonMatches('error', 'Invalid credentials.');
        }

        $this
            ->attemptJsonLogin($peter->getEmail(), 'wrong password', 403)
            ->assertJsonMatches('error', 'Too many login attempts');
    }

    public function testCatchBruteForceAttemptInForm()
    {
        $peter = $this->getReference('user.peter');

        for ($i = 0; $i < 2; ++$i) {
            $this
                ->attemptFormLogin($peter->getEmail(), 'wrong password')
                ->assertContains('Invalid credentials.');
        }

        $this
            ->attemptFormLogin($peter->getEmail(), 'wrong password')
            ->assertContains('Too many login attempts');;
    }

    public function testClearLoginAttempts()
    {
        /** @var \Anyx\LoginGateBundle\Service\BruteForceChecker $bruteForceChecker */
        $bruteForceChecker = static::getContainer()->get('anyx.login_gate.brute_force_checker');

        $request = $this->browser()->visit('web')->client()->getRequest();

        $peter = $this->getReference('user.peter');
        $this->assertEquals(0, $bruteForceChecker->getStorage()->getCountAttempts($request, $peter->getUsername()));

        $this->attemptJsonLogin($peter->getEmail(), 'wrong password', 401);
        $this->assertEquals(1, $bruteForceChecker->getStorage()->getCountAttempts($request, $peter->getUsername()));

        $this->attemptJsonLogin($peter->getEmail(), 'password');
        $this->assertEquals(0, $bruteForceChecker->getStorage()->getCountAttempts($request, $peter->getUsername()));
    }

    public function testCheckUsernamesFromSameIpAddress()
    {
        $peter = $this->getReference('user.peter');
        $helen = $this->getReference('user.helen');

        $this->browser()->visit('web');

        $this->attemptJsonLogin($peter->getEmail(), 'wrong password', 401);
        $this->attemptJsonLogin($peter->getEmail(), 'wrong password', 401);
        $this->attemptJsonLogin($peter->getEmail(), 'wrong password', 401);

        $this->attemptJsonLogin($peter->getEmail(), 'wrong password', 403)
            ->assertJsonMatches('error', 'Too many login attempts');

        $this->attemptJsonLogin($helen->getEmail(), 'password')
            ->assertStatus(200);

        $this
            ->attemptJsonLogin($peter->getEmail(), 'wrong password', 403)
            ->assertJsonMatches('error', 'Too many login attempts');
    }

    protected function attemptJsonLogin(string $username, string $password, int $status = 200): KernelBrowser
    {
        return $this
            ->browser()
            ->post('/api/login', ['json' => ['username' => $username, 'password' => $password]])
            ->assertStatus($status);
    }

    protected function attemptFormLogin(string $username, string $password): KernelBrowser
    {
        return $this->browser()
            ->followRedirects()
            ->get('/web/login')
            ->assertSuccessful()
            ->fillField('email', $username)
            ->fillField('password', $password)
            ->click('Sign in');
    }

    protected function getReference(string $name)
    {
        return $this->getReferenceRepository()->getReference($name);
    }

    protected function getReferenceRepository(): ReferenceRepository
    {
        return self::$referenceRepository;
    }

    protected static function executeCommand(string $command, array $options = [])
    {
        $application = new Application(static::$kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(
            array_merge(
                [
                    'command' => $command,
                ],
                $options
            )
        );

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
