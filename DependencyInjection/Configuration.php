<?php
namespace Anyx\LoginGateBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('login_gate');

        $rootNode
            ->children()
                ->scalarNode('max_count_attempts')->defaultValue(3)->end()
                ->scalarNode('timeout')->defaultValue(3000)->end()
                ->scalarNode('storage_type')
                    ->validate()
                    ->ifNotInArray(array('session', 'orm'))
                    ->thenInvalid("Invalid storage type '%s'. Available types: 'session', 'orm'")
        ;                
        
        return $treeBuilder;
    }

}
