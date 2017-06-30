<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Plugin;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

interface PluginConfigurationInterface extends ConfigurationInterface
{
    /**
     * @param NodeDefinition $rootNode
     *
     * @return NodeDefinition
     */
    public function buildConfigNode(NodeDefinition $rootNode);
}
