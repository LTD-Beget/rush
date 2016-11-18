<?php

namespace LTDBeget\Rush;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('rush');

        $rootNode
            ->children()
                ->scalarNode('prompt')
                    ->defaultValue('rush>')
                ->end()
                ->arrayNode('ignore')
                    ->prototype('scalar')->end()
                ->end()
                ->booleanNode('show_trace')
                    ->defaultFalse()
                ->end()
                ->enumNode('show_help')
                    ->values(['always', 'once', 'never'])
                    ->defaultValue('once')
            ->end();

        return $treeBuilder;
    }
}