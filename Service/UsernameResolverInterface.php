<?php

namespace Anyx\LoginGateBundle\Service;

use Symfony\Component\HttpFoundation\Request;

interface UsernameResolverInterface
{
    public function resolve(Request $request): ?string;
}
