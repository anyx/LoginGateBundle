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

    public function __construct(StorageInterface $storage, array $options)
    {
        $this->storage = $storage;
        $this->options = $options;
    }

    /**
     * @return bool
     */
    public function canLogin(Request $request)
    {
        if ($this->getStorage()->getCountAttempts($request) >= $this->options['max_count_attempts']) {
            $lastAttemptDate = $this->getStorage()->getLastAttemptDate($request);
            $dateAllowLogin = $lastAttemptDate->modify('+'.$this->options['timeout'].' second');

            if (1 === $dateAllowLogin->diff(new \DateTime())->invert) {
                return false;
            }
        }

        return true;
    }
}
