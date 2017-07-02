<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\PHPUnit;

use Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\PluginConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements PluginConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $this->buildConfigNode($treeBuilder->root('phpunit'));

        return $treeBuilder;
    }

    public function buildConfigNode(NodeDefinition $rootNode)
    {
        $rootNode
            ->beforeNormalization()->castToArray()->end()
            ->prototype('scalar')
            ->end();

        return $rootNode;
    }
}
