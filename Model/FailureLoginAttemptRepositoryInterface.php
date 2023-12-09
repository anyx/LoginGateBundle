<?php

namespace Anyx\LoginGateBundle\Model;

interface FailureLoginAttemptRepositoryInterface
{
    public function getCountAttempts(string $ip, ?string $username, \DateTime $startDate): int;

    public function getLastAttempt(string $ip, ?string $username): ?FailureLoginAttemptInterface;

    public function clearAttempts(string $ip, ?string $username): void;
}
