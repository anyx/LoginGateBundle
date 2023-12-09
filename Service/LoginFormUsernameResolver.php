<?php

namespace Anyx\LoginGateBundle\Service;

use Symfony\Component\HttpFoundation\Request;

class LoginFormUsernameResolver implements UsernameResolverInterface
{
    public function resolve(Request $request): ?string
    {
        return $request->get('_username');
    }
}
