<?php

namespace Anyx\LoginGateBundle\Security;

use Anyx\LoginGateBundle\Storage\StorageInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class AuthenticationHandler implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @var \Anyx\LoginGateBundle\Storage\StorageInterface
     */
    private $storage;

    public function __construct(RequestStack $requestStack, StorageInterface $storage)
    {
        $this->requestStack = $requestStack;
        $this->storage = $storage;
    }

    public static function getSubscribedEvents()
    {
        return [
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        $request = $this->getRequestStack()->getCurrentRequest();
        $this->getStorage()->incrementCountAttempts($request, $event->getAuthenticationException());
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $request = $this->getRequestStack()->getCurrentRequest();
        $this->getStorage()->clearCountAttempts($request);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack()
    {
        return $this->requestStack;
    }

    /**
     * @return \Anyx\LoginGateBundle\Storage\StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }
}
