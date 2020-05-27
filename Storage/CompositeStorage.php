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

    public function clearCountAttempts(Request $request, ?string $username): void
    {
        foreach ($this->getStorages() as $storage) {
            $storage->clearCountAttempts($request, $username);
        }
    }

    public function getCountAttempts(Request $request, ?string $username): int
    {
        $countAttempts = [];

        foreach ($this->getStorages() as $storage) {
            $countAttempts[] = $storage->getCountAttempts($request, $username);
        }

        return (int) max($countAttempts);
    }

    public function getLastAttemptDate(Request $request, ?string $username): ?\DateTimeInterface
    {
        $date = null;
        foreach ($this->getStorages() as $storage) {
            $storageDate = $storage->getLastAttemptDate($request, $username);
            if (!empty($storageDate) && (empty($date) || 1 == $storageDate->diff($date)->invert)) {
                $date = $storageDate;
            }
        }

        return $date;
    }

    public function incrementCountAttempts(Request $request, ?string $username, AuthenticationException $exception): void
    {
        foreach ($this->getStorages() as $storage) {
            $storage->incrementCountAttempts($request, $username, $exception);
        }
    }
}
