<?php

namespace Anyx\LoginGateBundle\DependencyInjection\CompilerPath;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Authentication implements CompilerPassInterface
{
    /**
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('security.authentication.listener.form')
                    ->setClass($container->getParameter('anyx.authentication.listener.form.class'))
                    ->addMethodCall(
                            'setBruteForceChecker',
                            array(
                                $container->findDefinition('anyx.login_failure.brute_force_checker')
                            )
                    )
                    ->addMethodCall(
                            'setDispatcher',
                            array(
                                $container->findDefinition('event_dispatcher')
                            )
                    )
                
        ;
    }
}