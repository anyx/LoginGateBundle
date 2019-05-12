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
        $treeBuilder = new TreeBuilder('login_gate');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('login_gate');
        }

        $rootNode
            ->children()
                ->scalarNode('watch_period')->defaultValue(10000)->end()
                ->arrayNode('options')
                    ->children()
                        ->scalarNode('max_count_attempts')->defaultValue(3)->end()
                        ->scalarNode('timeout')->defaultValue(3000)->end()
                        ->scalarNode('watch_period')->defaultValue(6000)->end()
                    ->end()
                ->end()
                ->arrayNode('storages')
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')
                    ->validate()
                    ->ifNotInArray(['session', 'orm', 'mongodb'])
                    ->thenInvalid("Invalid storage type '%s'. Available types: 'session', 'orm', 'mongodb'")
                ->end()
        ;

        return $treeBuilder;
    }

}
