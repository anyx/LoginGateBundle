<?php

namespace Anyx\LoginGateBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository as Repository;
use Anyx\LoginGateBundle\Model\FailureLoginAttemptRepositoryInterface;

class FailureLoginAttemptRepository extends Repository implements FailureLoginAttemptRepositoryInterface
{
    /**
     * @param string $ip
     * @param \DateTime $startDate
     * @return integer
     */
    public function getCountAttempts($ip, \DateTime $startDate)
    {
        return $this->createQueryBuilder()
            ->field('ip')->equals($ip)
            ->field('createdAt')->gt($startDate)
            ->getQuery()->count();
    }

    /**
     *
     * @param string $ip
     * @return \Anyx\LoginGateBundle\Model\FailureLoginAttempt | null
     */
    public function getLastAttempt($ip)
    {
        return $this->createQueryBuilder()
            ->field('ip')->equals($ip)
            ->sort('createdAt', 'desc')
            ->getQuery()
            ->getSingleResult();
    }

    /**
     *
     * @param string $ip
     * @return integer
     */
    public function clearAttempts($ip)
    {
        return $this->createQueryBuilder()
            ->remove()
            ->field('ip')->equals($ip)
            ->getQuery()
            ->execute();
    }
}
