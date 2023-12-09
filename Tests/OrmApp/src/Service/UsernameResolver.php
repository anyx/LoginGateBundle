<?php

namespace OrmApp\Service;

use Anyx\LoginGateBundle\Service\UsernameResolverInterface;
use Symfony\Component\HttpFoundation\Request;

class UsernameResolver implements UsernameResolverInterface
{
    public function resolve(Request $request): ?string
    {
        $requestData = json_decode($request->getContent(), true);

        return is_array($requestData) && array_key_exists('username', $requestData) ? $requestData['username'] : null;
    }
}
