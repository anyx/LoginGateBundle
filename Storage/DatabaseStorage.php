<?php

namespace Anyx\LoginGateBundle\Storage;

use Anyx\LoginGateBundle\Exception\BruteForceAttemptException;
use Anyx\LoginGateBundle\Model\FailureLoginAttempt;
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
     * @param int $watchPeriod
     */
    public function __construct(ObjectManager $objectManager, $entityClass, $watchPeriod)
    {
        $this->objectManager = $objectManager;
        $this->modelClassName = $entityClass;
        $this->watchPeriod = $watchPeriod;
    }

    public function clearCountAttempts(Request $request, ?string $username): void
    {
        if (!$this->hasIp($request)) {
            return;
        }

        $this->getRepository()->clearAttempts($request->getClientIp(), $username);
    }

    public function getCountAttempts(Request $request, ?string $username): int
    {
        if (!$this->hasIp($request)) {
            return 0;
        }

        $startWatchDate = new \DateTime();
        $startWatchDate->modify('-' . $this->getWatchPeriod() . ' second');

        return $this->getRepository()->getCountAttempts($request->getClientIp(), $username, $startWatchDate);
    }

    public function getLastAttemptDate(Request $request, ?string $username): ?\DateTimeInterface
    {
        if (!$this->hasIp($request)) {
            return null;
        }

        $lastAttempt = $this->getRepository()->getLastAttempt($request->getClientIp(), $username);
        if (!empty($lastAttempt)) {
            return $lastAttempt->getCreatedAt();
        }

        return null;
    }

    public function incrementCountAttempts(Request $request, ?string $username, AuthenticationException $exception): void
    {
        if ($exception instanceof BruteForceAttemptException) {
            return;
        }

        if (!$this->hasIp($request)) {
            return;
        }
        $model = $this->createModel();

        $model->setIp($request->getClientIp());
        $model->setUsername($username);

        $data = [
            'exception' => $exception->getMessage(),
            'clientIp' => $request->getClientIp(),
            'sessionId' => $request->getSession()->getId(),
        ];

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
     * @return FailureLoginAttempt
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
