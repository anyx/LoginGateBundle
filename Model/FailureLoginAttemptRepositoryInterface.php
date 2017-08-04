<?php

namespace Anyx\LoginGateBundle\Model;

interface FailureLoginAttemptRepositoryInterface
{
    /**
     * @param string $ip
     * @param \DateTime $startDate
     * @return integer
     */
    public function getCountAttempts($ip, \DateTime $startDate);

    /**
     * @param string $ip
     * @return \Anyx\LoginGateBundle\Model\FailureLoginAttempt | null
     */
    public function getLastAttempt($ip);

    /**
     * @param string $ip
     * @return integer
     */
    public function clearAttempts($ip);
}
