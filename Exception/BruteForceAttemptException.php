<?php

namespace Anyx\LoginGateBundle\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class BruteForceAttemptException extends AuthenticationException
{
    /**
     * @return string
     */
    public function getMessageKey(): string
    {
        return 'Too many authentication failures.';
    }
}
