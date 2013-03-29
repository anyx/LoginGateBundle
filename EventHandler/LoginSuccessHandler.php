<?php

namespace Anyx\LoginGateBundle\EventHandler;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class LoginSuccessHandler extends AbstractHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $this->getStorage()->clearCountAttempts($request);
    }
}