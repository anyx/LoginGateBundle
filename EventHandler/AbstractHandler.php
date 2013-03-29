<?php

namespace Anyx\LoginGateBundle\EventHandler;

use Anyx\LoginGateBundle\Storage\StorageInterface;


abstract class AbstractHandler
{
    /**
     *
     * @var \Anyx\LoginGateBundle\Storage\StorageInterface
     */
    protected $storage;

    /**
     * 
     * @param \Anyx\LoginGateBundle\Storage\StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }
    
    /**
     * 
     * @return \Anyx\LoginGateBundle\Storage\StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }
}