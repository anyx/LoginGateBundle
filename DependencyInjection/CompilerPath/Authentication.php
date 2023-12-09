<?php

namespace Anyx\LoginGateBundle\DependencyInjection\CompilerPath;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class Authentication implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $compositeStorageDefinition = $container->getDefinition('anyx.login_gate.attempt_storage');
        $chosenStorages = [];

        foreach ($container->getParameter('anyx.login_gate.storages') as $storageName) {
            $chosenStorages[] = new Reference($storageName);
        }

        $compositeStorageDefinition->setArguments([$chosenStorages]);
    }
}
