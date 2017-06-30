<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Configuration;

use Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\PluginInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class BuildConfiguration implements ConfigurationInterface
{
    /**
     * @var PluginInterface[]
     */
    private $plugins;

    /**
     * BuildConfiguration constructor.
     *
     * @param PluginInterface[] $plugins
     */
    public function __construct(array $plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * @return PluginInterface[]
     */
    public function getPlugins(): array
    {
        return $this->plugins;
    }

    /**
     * @param PluginInterface $plugin
     *
     * @return $this
     */
    public function addPlugin(PluginInterface $plugin): BuildConfiguration
    {
        $this->plugins[] = $plugin;

        return $this;
    }

    /**
     * @param PluginInterface[] $plugins
     *
     * @return $this
     */
    public function setPlugins(array $plugins): BuildConfiguration
    {
        foreach ($plugins as $plugin) {
            $this->addPlugin($plugin);
        }

        return $this;
    }

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

        $baseNode = $node
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->arrayPrototype()
        ;

        $baseNode->beforeNormalization()->always(function($value) {
            return [
                'plugin' => key($value),
                key($value) => $value[key($value)],
            ];
        })
            ->end()
            ->children()
                ->scalarNode('plugin')
            ->end();

        /** @var ArrayNodeDefinition $node */
        foreach ($this->plugins as $plugin) {
            $builder = new TreeBuilder();
            $root = $builder->root($plugin->getCode());

            $plugin->getConfiguration()->buildConfigNode($root);


            $baseNode->append($root);
        }

        return $node;
    }

    public function addPluginNodes()
    {
        $builder = new TreeBuilder();
        $root = $builder->root('plugins');

        $node = $root->arrayPrototype()->children();
        /** @var ArrayNodeDefinition $node */
        foreach ($this->plugins as $plugin) {
            $plugin->getConfiguration()->buildConfigNode(
                $node->arrayNode($plugin->getCode())
            );
        }

        return $root;
    }
}
