<?php

namespace Anyx\LoginGateBundle\Storage;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Anyx\LoginGateBundle\Exception\BruteForceAttemptException;

class OrmStorage implements StorageInterface
{
    /**
     * @var \Anyx\LoginGateBundle\Entity\FailureLoginAttemptRepository
     */
    private $entityManager;
    
    /**
     * @var string
     */
    private $entityClass;

    /**
     * @var integer
     */
    private $watchPeriod = 200;

    /**
     * 
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param string $entityClass
     * @param integer $watchPeriod
     */
    public function __construct(EntityManager $entityManager, $entityClass, $watchPeriod)
    {
        $this->entityManager = $entityManager;
        $this->entityClass = $entityClass;
        $this->watchPeriod = $watchPeriod;
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return integer
     */
    public function clearCountAttempts(Request $request)
    {
        if (!$this->hasIp($request)) {
            return;
        }
        
        $this->getRepository()->clearAttempts($request->getClientIp());
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return integer
     */
    public function getCountAttempts(Request $request)
    {
        if (!$this->hasIp($request)) {
            return;
        }
        $startWatchDate = new \DateTime();
        $startWatchDate->modify('-' . $this->getWatchPeriod(). ' second');
        
        return $this->getRepository()->getCountAttempts($request->getClientIp(), $startWatchDate);
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \DateTime|false
     */
    public function getLastAttemptDate(Request $request)
    {
        if (!$this->hasIp($request)) {
            return;
        }
        
        $lastAttempt = $this->getRepository()->getLastAttempt($request->getClientIp());
        if (!empty($lastAttempt)) {
            return $lastAttempt->getCreatedAt();
        }
        
        return false;
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     */
    public function incrementCountAttempts(Request $request, AuthenticationException $exception)
    {
        if ($exception instanceof BruteForceAttemptException) {
            return;
        }
        
        if (!$this->hasIp($request)) {
            return;
        }
        $entity = $this->createEntity();
        
        $entity->setIp($request->getClientIp());

        $data = array(
            'exception' => $exception->getMessage()
        );
        
        $userInforamtion = $exception->getExtraInformation();
        if (!empty($userInforamtion)) {
            $data['user'] = $userInforamtion->getUser();
        }
        
        $entity->setData($data);
        
        $em = $this->getEntityManager();
        
        $em->persist($entity);
        $em->flush($entity);
    }

    /**
     * 
     * @return integer
     */
    protected function getWatchPeriod()
    {
        return $this->watchPeriod;
    }

    /**
     * 
     */
    protected function createEntity()
    {
        return new $this->entityClass;
    }

    /**
     * 
     * @return \Anyx\LoginGateBundle\Entity\FailureLoginAttemptRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->entityClass);
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return boolean
     */
    protected function hasIp(Request $request)
    {
        return $request->getClientIp() != '';
    }
}