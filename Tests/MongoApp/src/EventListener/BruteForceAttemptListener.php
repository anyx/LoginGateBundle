<?php

namespace MongoApp\EventListener;

use Anyx\LoginGateBundle\Event\BruteForceAttemptEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class BruteForceAttemptListener
{
    public function onBruteForceAttempt(BruteForceAttemptEvent $event)
    {
        /*
        $event->getRequestEvent()->setResponse(new JsonResponse(['error' => 'Too many login attempts']));
        $event->getRequestEvent()->getResponse()
            ->setStatusCode(401);
         */

        throw new AccessDeniedHttpException('Too many login attempts');
    }
}
