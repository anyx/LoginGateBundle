<?php

namespace Anyx\LoginGateBundle\Storage;

use Anyx\LoginGateBundle\Exception\BruteForceAttemptException;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class DatabaseStorage implements StorageInterface
{
    /**
     * @var string
     */
    private $modelClassName;

    /**
     * @var int
     */
    private $watchPeriod = 200;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $objectManager;

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @param string $entityClass
     * @param int    $watchPeriod
     */
    public function __construct(ObjectManager $objectManager, $entityClass, $watchPeriod)
    {
        $this->objectManager = $objectManager;
        $this->modelClassName = $entityClass;
        $this->watchPeriod = $watchPeriod;
    }

    public function clearCountAttempts(Request $request)
    {
        if (!$this->hasIp($request)) {
            return;
        }

        $this->getRepository()->clearAttempts($request->getClientIp());
    }

    /**
     * @return int
     */
    public function getCountAttempts(Request $request)
    {
        if (!$this->hasIp($request)) {
            return 0;
        }
        $startWatchDate = new \DateTime();
        $startWatchDate->modify('-'.$this->getWatchPeriod().' second');

        return $this->getRepository()->getCountAttempts($request->getClientIp(), $startWatchDate);
    }

    /**
     * @return \DateTime|false
     */
    public function getLastAttemptDate(Request $request)
    {
        if (!$this->hasIp($request)) {
            return false;
        }

        $lastAttempt = $this->getRepository()->getLastAttempt($request->getClientIp());
        if (!empty($lastAttempt)) {
            return $lastAttempt->getCreatedAt();
        }

        return false;
    }

    public function incrementCountAttempts(Request $request, AuthenticationException $exception)
    {
        if ($exception instanceof BruteForceAttemptException) {
            return;
        }

        if (!$this->hasIp($request)) {
            return;
        }
        $model = $this->createModel();

        $model->setIp($request->getClientIp());

        $data = [
            'exception' => $exception->getMessage(),
            'clientIp' => $request->getClientIp(),
            'sessionId' => $request->getSession()->getId(),
        ];

        $username = $request->get('_username');
        if (!empty($username)) {
            $data['user'] = $username;
        }

        $model->setData($data);

        $objectManager = $this->getObjectManager();

        $objectManager->persist($model);
        $objectManager->flush();
    }

    /**
     * @return int
     */
    protected function getWatchPeriod()
    {
        return $this->watchPeriod;
    }

    /**
     * @return string
     */
    protected function createModel()
    {
        return new $this->modelClassName();
    }

    /**
     * @return \Anyx\LoginGateBundle\Model\FailureLoginAttemptRepositoryInterface
     */
    protected function getRepository()
    {
        return $this->getObjectManager()->getRepository($this->modelClassName);
    }

    /**
     * @return bool
     */
    protected function hasIp(Request $request)
    {
        return '' != $request->getClientIp();
    }
}
