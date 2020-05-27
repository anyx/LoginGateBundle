<?php

namespace Anyx\LoginGateBundle\Service;

use Symfony\Component\HttpFoundation\Request;

interface UsernameResolverInterface
{
    /**
     * @return string|null
     */
    public function resolve(Request $request);
}
