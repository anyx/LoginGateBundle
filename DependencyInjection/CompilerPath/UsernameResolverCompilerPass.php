<?php

namespace Anyx\LoginGateBundle\DependencyInjection\CompilerPath;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class UsernameResolverCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasAlias('anyx.login_gate.username_resolver')) {
            $usernameResolverDefinition = $container->findDefinition('anyx.login_gate.username_resolver');
            $container->getDefinition('anyx.login_gate.security_subscriber')->addArgument($usernameResolverDefinition);
        }
    }
}
