<?php

namespace Anyx\LoginGateBundle;

use Anyx\LoginGateBundle\DependencyInjection\CompilerPath;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LoginGateBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CompilerPath\Authentication());
        $container->addCompilerPass(new CompilerPath\UsernameResolverCompilerPass());
    }
}
