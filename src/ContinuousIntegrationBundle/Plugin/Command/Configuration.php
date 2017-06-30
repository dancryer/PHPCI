<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\Command;

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
            ->cannotBeEmpty()
            ->beforeNormalization()->castToArray()->end()
            ->prototype('scalar');

        return $rootNode;
    }
}
