<?php

namespace Anyx\LoginGateBundle\Entity;

use Doctrine\ORM\EntityRepository as Repository;
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
        return $this->createQueryBuilder('attempt')
                    ->select('COUNT(attempt.id)')
                    ->where('attempt.ip = :ip')
                    ->andWhere('attempt.createdAt > :createdAt')
                    ->setParameters(array(
                        'ip'        => $ip,
                        'createdAt' => $startDate
                    ))
                    ->getQuery()
                    ->getSingleScalarResult();
    }
    
    /**
     * 
     * @param string $ip
     * @return \Anyx\LoginGateBundle\Entity\FailureLoginAttempt | null
     */
    public function getLastAttempt($ip)
    {
        return $this->createQueryBuilder('attempt')
                    ->where('attempt.ip = :ip')
                    ->orderBy('attempt.createdAt', 'DESC')
                    ->setParameters(array(
                        'ip'        => $ip
                    ))
                    ->getQuery()
                    ->setMaxResults(1)
                    ->getOneOrNullResult()
        ;
    }
    
    /**
     * @param string $ip
     * @return integer
     */
    public function clearAttempts($ip)
    {
        return $this->getEntityManager()
                ->createQuery('DELETE FROM ' . $this->getClassMetadata()->name . ' attempt WHERE attempt.ip = :ip')
                ->execute(['ip' => $ip])
            ;
    }
}
