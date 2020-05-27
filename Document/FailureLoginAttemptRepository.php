<?php

namespace Anyx\LoginGateBundle\Document;

use Anyx\LoginGateBundle\Model;
use Anyx\LoginGateBundle\Model\FailureLoginAttemptRepositoryInterface;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository as BaseRepository;

class FailureLoginAttemptRepository extends BaseRepository implements FailureLoginAttemptRepositoryInterface
{
    public function getCountAttempts(string $ip, ?string $username, \DateTime $startDate): int
    {
        return $this->createQueryBuilder()
            ->field('ip')->equals($ip)
            ->field('username')->equals($username)
            ->count()
            ->field('createdAt')->gt($startDate)
            ->getQuery()
            ->execute();
    }

    public function getLastAttempt(string $ip, ?string $username): ?Model\FailureLoginAttempt
    {
        return $this->createQueryBuilder()
            ->field('ip')->equals($ip)
            ->field('username')->equals($username)
            ->sort('createdAt', 'desc')
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @return int
     */
    public function clearAttempts(string $ip, ?string $username): void
    {
        $this->createQueryBuilder()
            ->remove()
            ->field('ip')->equals($ip)
            ->field('username')->equals($username)
            ->getQuery()
            ->execute();
    }
}
