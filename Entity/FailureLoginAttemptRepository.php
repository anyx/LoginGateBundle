<?php

namespace Anyx\LoginGateBundle\Entity;

use Anyx\LoginGateBundle\Model;
use Anyx\LoginGateBundle\Model\FailureLoginAttemptRepositoryInterface;
use Doctrine\ORM\EntityRepository as Repository;

class FailureLoginAttemptRepository extends Repository implements FailureLoginAttemptRepositoryInterface
{
    public function getCountAttempts(string $ip, ?string $username, \DateTime $startDate): int
    {
        $queryBuilder = $this->createQueryBuilder('attempt')
            ->select('COUNT(attempt.id)')
            ->where('attempt.ip = :ip')
            ->andWhere('attempt.createdAt > :createdAt')
            ->setParameters([
                'ip' => $ip,
                'createdAt' => $startDate,
            ]);

        if ($username) {
            $queryBuilder->andWhere('attempt.username = :username')->setParameter('username', $username);
        }

        return $queryBuilder
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getLastAttempt(string $ip, ?string $username): ?Model\FailureLoginAttempt
    {
        $queryBuilder = $this->createQueryBuilder('attempt')
            ->where('attempt.ip = :ip')
            ->orderBy('attempt.createdAt', 'DESC')
            ->setParameters([
                'ip' => $ip,
            ]);

        if ($username) {
            $queryBuilder->andWhere('attempt.username = :username')->setParameter('username', $username);
        }

        return $queryBuilder
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    public function clearAttempts(string $ip, ?string $username): void
    {
        $sql = 'DELETE FROM %s attempt WHERE attempt.ip = :ip';
        $parameters = ['ip' => $ip];

        if ($username) {
            $sql .= ' AND attempt.username = :username';
            $parameters['username'] = $username;
        }

        $this->getEntityManager()
            ->createQuery(
                sprintf(
                    $sql,
                    $this->getClassMetadata()->name
                )
            )
            ->execute($parameters);
    }
}
