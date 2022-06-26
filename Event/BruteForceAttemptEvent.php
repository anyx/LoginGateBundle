<?php

namespace Anyx\LoginGateBundle\Event;

use Anyx\LoginGateBundle\Service\BruteForceChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class BruteForceAttemptEvent extends Event
{
    public function __construct(private Request $request, private BruteForceChecker $bruteForceChecker)
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getBruteForceChecker(): BruteForceChecker
    {
        return $this->bruteForceChecker;
    }
}
