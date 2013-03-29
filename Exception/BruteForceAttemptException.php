<?php

namespace Anyx\LoginGateBundle\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class BruteForceAttemptException extends AuthenticationException
{
    
}