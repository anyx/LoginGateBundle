<?php

namespace Anyx\LoginGateBundle\Entity;

use Anyx\LoginGateBundle\Model;
use Anyx\LoginGateBundle\Model\FailureLoginAttemptRepositoryInterface;
use Doctrine\ORM\EntityRepository as Repository;

class FailureLoginAttemptRepository extends Repository implements FailureLoginAttemptRepositoryInterface
{
    /**
     * @param string $ip
     */
    public function getCountAttempts($ip, \DateTime $startDate): int
    {
        return $this->createQueryBuilder('attempt')
            ->select('COUNT(attempt.id)')
            ->where('attempt.ip = :ip')
            ->andWhere('attempt.createdAt > :createdAt')
            ->setParameters([
                'ip' => $ip,
                'createdAt' => $startDate,
            ])
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param string $ip
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLastAttempt($ip): Model\FailureLoginAttempt
    {
        return $this->createQueryBuilder('attempt')
            ->where('attempt.ip = :ip')
            ->orderBy('attempt.createdAt', 'DESC')
            ->setParameters([
                'ip' => $ip,
            ])
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    /**
     * @param string $ip
     *
     * @return int
     */
    public function clearAttempts($ip): void
    {
        $this->getEntityManager()
            ->createQuery(sprintf('DELETE FROM %s attempt WHERE attempt.ip = :ip', $this->getClassMetadata()->name))
            ->execute(['ip' => $ip]);
    }
}
