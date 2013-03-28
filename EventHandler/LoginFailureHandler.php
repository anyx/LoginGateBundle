<?php

namespace Anyx\LoginGateBundle\EventHandler;

use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;


use Anyx\LoginGateBundle\Storage\StorageInterface;

class LoginFailureHandler implements AuthenticationFailureHandlerInterface
{
    /**
     *
     * @var \Anyx\LoginGateBundle\Storage\StorageInterface
     */
    protected $storage;

    /**
     * 
     * @param \Anyx\LoginGateBundle\Storage\StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }
    
    /**
     * 
     * @return \Anyx\LoginGateBundle\Storage\StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->getStorage()->incrementCountAttempts($request);
        
        $referer = $request->headers->get('referer');
        $request->getSession()->set(SecurityContext::AUTHENTICATION_ERROR, $exception);

        return new RedirectResponse($referer);
    }
}