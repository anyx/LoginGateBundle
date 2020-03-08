<?php

namespace Anyx\LoginGateBundle\Storage;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CompositeStorage implements StorageInterface
{
    /**
     * @var array
     */
    protected $storages = [];

    public function __construct(array $storages)
    {
        foreach ($storages as $storage) {
            $this->addStorage($storage);
        }
    }

    /**
     * @return array
     */
    public function getStorages()
    {
        return $this->storages;
    }

    /**
     * @param \Anyx\LoginGateBundle\Storage\StorageInterface $storage
     */
    public function addStorage(StorageInterface $storage)
    {
        $this->storages[] = $storage;
    }

    public function clearCountAttempts(Request $request)
    {
        foreach ($this->getStorages() as $storage) {
            $storage->clearCountAttempts($request);
        }
    }

    public function getCountAttempts(Request $request)
    {
        $countAttempts = [];

        foreach ($this->getStorages() as $storage) {
            $countAttempts[] = $storage->getCountAttempts($request);
        }

        return (int) max($countAttempts);
    }

    /**
     * @return \DateTime | false
     */
    public function getLastAttemptDate(Request $request)
    {
        $date = false;
        foreach ($this->getStorages() as $storage) {
            $storageDate = $storage->getLastAttemptDate($request);
            if (!empty($storageDate) && (empty($date) || 1 == $storageDate->diff($date)->invert)) {
                $date = $storageDate;
            }
        }

        return $date;
    }

    public function incrementCountAttempts(Request $request, AuthenticationException $exception)
    {
        foreach ($this->getStorages() as $storage) {
            $storage->incrementCountAttempts($request, $exception);
        }
    }
}
