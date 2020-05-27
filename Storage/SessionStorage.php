<?php

namespace Anyx\LoginGateBundle\Storage;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class SessionStorage implements StorageInterface
{
    const COUNT_LOGIN_ATTEMPTS = '_security.count_login_attempts';

    const DATE_LAST_LOGIN_ATTEMPT = '_security.last_failurelogin_attempt';

    public function clearCountAttempts(Request $request, ?string $username): void
    {
        $request->getSession()->remove(self::COUNT_LOGIN_ATTEMPTS);
        $request->getSession()->remove(self::DATE_LAST_LOGIN_ATTEMPT);
    }

    public function getCountAttempts(Request $request, ?string $username): int
    {
        return (int) $request->getSession()->get(self::COUNT_LOGIN_ATTEMPTS, 0);
    }

    public function incrementCountAttempts(Request $request, ?string $username, AuthenticationException $exception): void
    {
        $request->getSession()->set(self::COUNT_LOGIN_ATTEMPTS, $this->getCountAttempts($request, $username) + 1);
        $request->getSession()->set(self::DATE_LAST_LOGIN_ATTEMPT, new \DateTime());
    }

    public function getLastAttemptDate(Request $request, ?string $username): ?\DateTimeInterface
    {
        $session = $request->getSession();
        if ($session->has(self::DATE_LAST_LOGIN_ATTEMPT)) {
            return clone $session->get(self::DATE_LAST_LOGIN_ATTEMPT);
        }

        return null;
    }
}
