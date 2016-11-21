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

        $showHelp = [
            ConfigInterface::SHOW_HELP_NEVER,
            ConfigInterface::SHOW_HELP_ALWAYS,
            ConfigInterface::SHOW_HELP_ONCE,
        ];

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
                ->arrayNode('help')
                    ->children()
                        ->enumNode('show')
                            ->values($showHelp)
                            ->defaultValue(ConfigInterface::SHOW_HELP_ONCE)
                        ->end()
                        ->scalarNode('height')
                            ->defaultValue(10)
                        ->end()
                        ->booleanNode('sub')
                            ->defaultValue(false)
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}