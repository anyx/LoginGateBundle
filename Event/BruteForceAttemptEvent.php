<?php

namespace Anyx\LoginGateBundle\Event;

use Anyx\LoginGateBundle\Service\BruteForceChecker;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\EventDispatcher\Event;

class BruteForceAttemptEvent extends Event
{
    /**
     * @var RequestEvent
     */
    private $requestEvent;

    /**
     * @var \Anyx\LoginGateBundle\Service\BruteForceChecker
     */
    private $bruteForceChecker;

    public function __construct(RequestEvent $requestEvent, BruteForceChecker $bruteForceChecker)
    {
        $this->requestEvent = $requestEvent;
        $this->bruteForceChecker = $bruteForceChecker;
    }

    public function getRequestEvent(): RequestEvent
    {
        return $this->requestEvent;
    }

    /**
     * @return \Anyx\LoginGateBundle\Service\BruteForceChecker
     */
    public function getBruteForceChecker()
    {
        return $this->bruteForceChecker;
    }
}
