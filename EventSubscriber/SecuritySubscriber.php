<?php

namespace Anyx\LoginGateBundle\EventSubscriber;

use Anyx\LoginGateBundle\Event\BruteForceAttemptEvent;
use Anyx\LoginGateBundle\Exception\BruteForceAttemptException;
use Anyx\LoginGateBundle\Security\Events as SecurityEvents;
use Anyx\LoginGateBundle\Service\BruteForceChecker;
use Anyx\LoginGateBundle\Service\UsernameResolverInterface;
use Anyx\LoginGateBundle\Storage\StorageInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SecuritySubscriber implements EventSubscriberInterface
{
    private const SUBSCRIBER_PRIORITY = 10000;

    public function __construct(
        private RequestStack $requestStack,
        private StorageInterface $storage,
        private BruteForceChecker $bruteForceChecker,
        private EventDispatcherInterface $eventDispatcher,
        private ?UsernameResolverInterface $usernameProvider = null
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            LoginFailureEvent::class => ['onLoginFailure', self::SUBSCRIBER_PRIORITY],
            LoginSuccessEvent::class => ['onLoginSuccess', self::SUBSCRIBER_PRIORITY],
            CheckPassportEvent::class => ['onCheckPassport', self::SUBSCRIBER_PRIORITY],
        ];
    }

    public function onLoginFailure(LoginFailureEvent $event)
    {
        $request = $this->getRequestStack()->getCurrentRequest();

        $this->getStorage()->incrementCountAttempts(
            $request,
            $this->getUsername($request),
            $event->getException()
        );
    }

    public function onLoginSuccess(LoginSuccessEvent $event)
    {
        $request = $this->getRequestStack()->getCurrentRequest();
        $this->getStorage()->clearCountAttempts($request, $this->getUsername($request));
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {
        $request = $this->getRequestStack()->getCurrentRequest();

        if (!$this->getBruteForceChecker()->canLogin($request)) {
            $bruteForceEvent = new BruteForceAttemptEvent($request, $this->getBruteForceChecker());

            $this->getEventDispatcher()->dispatch($bruteForceEvent, SecurityEvents::BRUTE_FORCE_ATTEMPT);

            throw new BruteForceAttemptException('Brute force attempt');
        }
    }

    public function getRequestStack(): RequestStack
    {
        return $this->requestStack;
    }

    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    public function getUsernameProvider(): ?UsernameResolverInterface
    {
        return $this->usernameProvider;
    }

    public function getBruteForceChecker(): BruteForceChecker
    {
        return $this->bruteForceChecker;
    }

    protected function getUsername(Request $request)
    {
        if (!$this->usernameProvider) {
            return null;
        }

        return $this->usernameProvider->resolve($request);
    }
}
