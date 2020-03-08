<?php

namespace Anyx\LoginGateBundle\Model;

interface FailureLoginAttemptRepositoryInterface
{
    /**
     * @return int
     */
    public function getCountAttempts(string $ip, \DateTime $startDate);

    /**
     * @return FailureLoginAttempt | null
     */
    public function getLastAttempt(string $ip): ?FailureLoginAttempt;

    /**
     * @return int
     */
    public function clearAttempts(string $ip): void;
}
