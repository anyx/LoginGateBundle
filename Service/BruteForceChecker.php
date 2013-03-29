<?php

namespace Anyx\LoginGateBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Anyx\LoginGateBundle\Storage\StorageInterface;

/**
 * 
 */
class BruteForceChecker
{
    /**
     *
     * @var \Anyx\LoginGateBundle\Storage\StorageInterface
     */
    protected $storage;
    
    /**
     * @var array
     */
    private $options = array(
        'max_count_attempts'    => 3,
        'timeout'               => 60
    );

    /**
     * 
     * @return \Anyx\LoginGateBundle\Storage\StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * 
     * @param \Anyx\LoginGateBundle\Storage\StorageInterface $storage
     * @param array $options
     */
    public function __construct(StorageInterface $storage, array $options)
    {
        $this->storage = $storage;
        $this->options = $options;
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return boolean
     */
    public function canLogin(Request $request)
    {
        if ($this->getStorage()->getCountAttempts($request) > $this->options['max_count_attempts']) {

            $lastAttemptDate = $this->getStorage()->getLastAttemptDate($request);
            $dateAllowLogin = $lastAttemptDate->modify('+' . $this->options['timeout'] . ' second');

            if ($dateAllowLogin->diff(new \DateTime())->invert === 1) {
                return false;
            }
        }
        return true;
    }
}
