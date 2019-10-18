<?php

namespace Aldaflux\SireneApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
         $treeBuilder = new TreeBuilder();
 
	 $rootNode = $treeBuilder->root('aldaflux_sirene_api');
 
        $rootNode
            ->children()
                ->arrayNode('credentials')
                    ->children()
                        ->scalarNode('sirene_key')->end()
                        ->scalarNode('sirene_secret')->end()
                    ->end()
                ->end() // twitter
            ->end()
        ;
        return $treeBuilder;
        
//        $treeBuilder = new TreeBuilder();
        
        /*
	 $treeBuilder = new TreeBuilder('aldaflux_sirene_api');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('credentials')
                    ->children()
                        ->scalarNode('sirene_key')->end()
                        ->scalarNode('sirene_secret')->end()
                    ->end()
                ->end() // twitter
            ->end()
        ;
        return $treeBuilder;
         * 
         */
    }
}
