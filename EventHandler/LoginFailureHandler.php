<?php

namespace Anyx\LoginGateBundle\EventHandler;

use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;

class LoginFailureHandler extends AbstractHandler implements AuthenticationFailureHandlerInterface
{
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