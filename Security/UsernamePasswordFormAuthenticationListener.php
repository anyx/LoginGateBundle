<?php

namespace Anyx\LoginGateBundle\Security;

use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener as BaseListener;

class UsernamePasswordFormAuthenticationListener extends BaseListener
{
    use AuthenticationListenerTrait;
}
