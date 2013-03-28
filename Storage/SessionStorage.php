<?php

namespace Anyx\LoginGateBundle\Storage;

use Symfony\Component\HttpFoundation\Request;

class SessionStorage implements StorageInterface
{
    const COUNT_LOGIN_ATTEMPTS = '_security.count_login_attempts';
 
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function clearCountAttempts(Request $request)
    {
        $request->getSession()->remove(self::COUNT_LOGIN_ATTEMPTS);
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
    }
}