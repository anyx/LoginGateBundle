<?php

namespace Anyx\LoginGateBundle\Storage;

use Symfony\Component\HttpFoundation\Request;

class SessionStorage implements StorageInterface
{
    const COUNT_LOGIN_ATTEMPTS = '_security.count_login_attempts';

    const DATE_LAST_LOGIN_ATTEMPT = '_security.last_failurelogin_attempt';
    
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function clearCountAttempts(Request $request)
    {
        $request->getSession()->remove(self::COUNT_LOGIN_ATTEMPTS);
        $request->getSession()->remove(self::DATE_LAST_LOGIN_ATTEMPT);
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return integer
     */
    public function getCountAttempts(Request $request)
    {
        return (int) $request->getSession()->get(self::COUNT_LOGIN_ATTEMPTS, 0);
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function incrementCountAttempts(Request $request)
    {
        $request->getSession()->set(self::COUNT_LOGIN_ATTEMPTS, $this->getCountAttempts($request) + 1);
        $request->getSession()->set(self::DATE_LAST_LOGIN_ATTEMPT, new \DateTime());
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \DateTime
     */
    public function getLastAttemptDate(Request $request)
    {
        $session = $request->getSession();
        if ($session->has(self::DATE_LAST_LOGIN_ATTEMPT)) {
            return clone $session->get(self::DATE_LAST_LOGIN_ATTEMPT);
        }
        
        return false;
    }
}