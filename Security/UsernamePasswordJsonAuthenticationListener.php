<?php

namespace Anyx\LoginGateBundle\Security;

use Symfony\Component\Security\Http\Firewall\UsernamePasswordJsonAuthenticationListener as BaseListener;

class UsernamePasswordJsonAuthenticationListener extends BaseListener
{
    use AuthenticationListenerTrait;
}
