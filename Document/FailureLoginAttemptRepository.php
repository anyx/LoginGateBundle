<?php

namespace Anyx\LoginGateBundle\Document;

use Anyx\LoginGateBundle\Model;
use Anyx\LoginGateBundle\Model\FailureLoginAttemptRepositoryInterface;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository as BaseRepository;

class FailureLoginAttemptRepository extends BaseRepository implements FailureLoginAttemptRepositoryInterface
{
    public function getCountAttempts(string $ip, \DateTime $startDate): int
    {
        return $this->createQueryBuilder()
            ->field('ip')->equals($ip)
            ->count()
            ->field('createdAt')->gt($startDate)
            ->getQuery()
            ->execute();
    }

    /**
     * @return FailureLoginAttempt | null
     */
    public function getLastAttempt(string $ip): ?Model\FailureLoginAttempt
    {
        return $this->createQueryBuilder()
            ->field('ip')->equals($ip)
            ->sort('createdAt', 'desc')
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param string $ip
     *
     * @return int
     */
    public function clearAttempts($ip): void
    {
        $this->createQueryBuilder()
            ->remove()
            ->field('ip')->equals($ip)
            ->getQuery()
            ->execute();
    }
}
