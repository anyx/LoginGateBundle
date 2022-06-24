<?php

namespace OrmApp\EventListener;

use Anyx\LoginGateBundle\Event\BruteForceAttemptEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class BruteForceAttemptListener
{
    public function onBruteForceAttempt(BruteForceAttemptEvent $event)
    {
        throw new AccessDeniedHttpException('Too many login attempts');
    }
}
