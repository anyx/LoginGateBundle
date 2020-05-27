<?php

namespace Anyx\LoginGateBundle\Service;

use Anyx\LoginGateBundle\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\Request;

class BruteForceChecker
{
    /**
     * @var \Anyx\LoginGateBundle\Storage\StorageInterface
     */
    protected $storage;

    /**
     * @var UsernameResolverInterface
     */
    protected $usernameResolver;

    /**
     * @var array
     */
    private $options = [
        'max_count_attempts' => 3,
        'timeout' => 60,
    ];

    /**
     * @return \Anyx\LoginGateBundle\Storage\StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    public function __construct(StorageInterface $storage, UsernameResolverInterface $usernameResolver, array $options)
    {
        $this->storage = $storage;
        $this->options = $options;
        $this->usernameResolver = $usernameResolver;
    }

    /**
     * @return bool
     */
    public function canLogin(Request $request)
    {
        $username = $this->usernameResolver->resolve($request);

        if ($this->getStorage()->getCountAttempts($request, $username) >= $this->options['max_count_attempts']) {
            $lastAttemptDate = $this->getStorage()->getLastAttemptDate($request, $username);
            $dateAllowLogin = $lastAttemptDate->modify('+' . $this->options['timeout'] . ' second');

            if (1 === $dateAllowLogin->diff(new \DateTime())->invert) {
                return false;
            }
        }

        return true;
    }
}
