<?php

namespace Anyx\LoginGateBundle\Model;

interface FailureLoginAttemptRepositoryInterface
{
    /**
     * @return int
     */
    public function getCountAttempts(string $ip, ?string $username, \DateTime $startDate);

    /**
     * @return FailureLoginAttempt | null
     */
    public function getLastAttempt(string $ip, ?string $username): ?FailureLoginAttempt;

    /**
     * @return int
     */
    public function clearAttempts(string $ip, ?string $username): void;
}
