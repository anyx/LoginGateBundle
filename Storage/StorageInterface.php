<?php

namespace Anyx\LoginGateBundle\Storage;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

interface StorageInterface
{
    /**
     * @return int
     */
    public function getCountAttempts(Request $request);

    public function incrementCountAttempts(Request $request, AuthenticationException $exception);

    public function clearCountAttempts(Request $request);

    public function getLastAttemptDate(Request $request);
}
