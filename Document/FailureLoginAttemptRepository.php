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
        if (!is_int($ip)) {
            $ip = ip2long($ip);
        }

        return $this->createQueryBuilder()
            ->field('ip')->equals($ip)
            ->field('createdAt')->gt($startDate)
            ->getQuery()->count();
    }

    /**
     *
     * @param integer $ip
     * @return \Anyx\LoginGateBundle\Model\FailureLoginAttempt | null
     */
    public function getLastAttempt($ip)
    {
        if (!is_int($ip)) {
            $ip = ip2long($ip);
        }

        return $this->createQueryBuilder()
            ->field('ip')->equals($ip)
            ->sort('createdAt', 'desc')
            ->getQuery()
            ->getSingleResult();
    }

    /**
     *
     * @param integer $ip
     * @return integer
     */
    public function clearAttempts($ip)
    {
        if (!is_int($ip)) {
            $ip = ip2long($ip);
        }

        return $this->createQueryBuilder()
            ->remove()
            ->field('ip')->equals($ip)
            ->getQuery()
            ->execute();
    }
}
