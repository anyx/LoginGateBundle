<?php

namespace Anyx\LoginGateBundle\Security;

use Anyx\LoginGateBundle\Event\BruteForceAttemptEvent;
use Anyx\LoginGateBundle\Exception\BruteForceAttemptException;
use Anyx\LoginGateBundle\Security\Events as SecurityEvents;
use Anyx\LoginGateBundle\Service\BruteForceChecker;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

trait AuthenticationListenerTrait
{
    /**
     * @var \Anyx\LoginGateBundle\Service\BruteForceChecker
     */
    protected $bruteForceChecker;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @return \Anyx\LoginGateBundle\Service\BruteForceChecker
     */
    public function getBruteForceChecker()
    {
        return $this->bruteForceChecker;
    }

    public function setBruteForceChecker(BruteForceChecker $bruteForceChecker)
    {
        $this->bruteForceChecker = $bruteForceChecker;
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function authenticate(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!$this->getBruteForceChecker()->canLogin($request)) {
            $bruteForceEvent = new BruteForceAttemptEvent($event, $this->getBruteForceChecker());

            $this->getDispatcher()->dispatch($bruteForceEvent, SecurityEvents::BRUTE_FORCE_ATTEMPT);

            throw new BruteForceAttemptException('Brute force attempt');
        }

        return parent::authenticate($event);
    }
}
