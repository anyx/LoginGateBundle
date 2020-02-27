<?php

namespace Anyx\LoginGateBundle\Security;

use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener as BaseListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Anyx\LoginGateBundle\Service\BruteForceChecker;
use Anyx\LoginGateBundle\Security\Events as SecurityEvents;
use Anyx\LoginGateBundle\Event\BruteForceAttemptEvent;
use Anyx\LoginGateBundle\Exception\BruteForceAttemptException;

class UsernamePasswordFormAuthenticationListener extends BaseListener
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

    /**
     * @param \Anyx\LoginGateBundle\Service\BruteForceChecker $bruteForceChecker
     */
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

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    protected function attemptAuthentication(Request $request)
    {
        if (!$this->getBruteForceChecker()->canLogin($request)) {

            $event = new BruteForceAttemptEvent($request, $this->getBruteForceChecker());

            $this->getDispatcher()->dispatch($event, SecurityEvents::BRUTE_FORCE_ATTEMPT);

            throw new BruteForceAttemptException('Brute force attempt');
        }
        
        return parent::attemptAuthentication($request);
    }
}
