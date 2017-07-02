<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\Docker;

use Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\PluginConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements PluginConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $this->buildConfigNode($treeBuilder->root('docker'));

        return $treeBuilder;
    }

    public function buildConfigNode(NodeDefinition $rootNode)
    {
        $rootNode
            ->beforeNormalization()->castToArray()->end()
            ->children()
                ->scalarNode('name')
                    ->cannotBeEmpty()
                    ->isRequired()
                ->end()
                ->scalarNode('machine')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('dockerfile')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('image')
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $rootNode;
    }
}
