<?php

namespace Anyx\LoginGateBundle\Storage;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

interface StorageInterface
{
    public function getCountAttempts(Request $request, ?string $username): int;

    public function incrementCountAttempts(Request $request, ?string $username, AuthenticationException $exception): void;

    public function clearCountAttempts(Request $request, ?string $username): void;

    public function getLastAttemptDate(Request $request, ?string $username): ?\DateTimeInterface;
}
