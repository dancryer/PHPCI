<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class BuildConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ci');

        $rootNode
            ->fixXmlConfig('stage')
            ->children()
                ->arrayNode('stages')
                ->end()
                ->append($this->addStagesNode())
            ->end();

        return $treeBuilder;
    }

    public function addStagesNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('stages');

        $node
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('code')
            ->prototype('array')
                ->fixXmlConfig('step')
                ->children()
                    ->scalarNode('label')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->enumNode('on-failure')
                        ->values(['ignore', 'warn', 'fatal'])
                        ->defaultValue('fatal')
                    ->end()
                    ->append($this->addStepsNode())
                ->end()
            ->end()
        ;

        return $node;
    }

    public function addStepsNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('steps');

        $node
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->prototype('array')
                ->variablePrototype()->end()
            ->end()
        ;

        return $node;
    }
}
