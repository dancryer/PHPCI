<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline;

use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\Processor\FingersCrossedProcessor;
use Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\PluginInterface;

class PipelineFactory
{
    /**
     * @var PluginInterface[]
     */
    private $plugins;

    /**
     * PipelineFactory constructor.
     * @param PluginInterface[] $plugins
     */
    public function __construct(array $plugins)
    {
        $this->plugins = [];
        $this->setPlugins($plugins);
    }

    /**
     * @param PluginInterface $plugin
     */
    public function addPlugin(PluginInterface $plugin)
    {
        $this->plugins[$plugin->getCode()] = $plugin;
    }

    /**
     * @param PluginInterface[] $plugins
     */
    public function setPlugins(array $plugins)
    {
        foreach ($plugins as $plugin) {
            $this->addPlugin($plugin);
        }
    }

    /**
     * @param array $config
     *
     * @return Stage[]
     */
    public function build(array $config): array
    {
        return iterator_to_array($this->buildStages($config));
    }

    /**
     * @param array $config
     *
     * @return Stage[]|\Traversable
     */
    private function buildStages(array $config): \Traversable
    {
        foreach ($config['stages'] as $stageCode => $stageConfig) {
            $stage = new Stage($stageCode, $stageConfig['label']);

            $this->buildSteps($stage, $stageConfig['steps']);

            if ($stageConfig['on-failure']) {
                $processor = new FingersCrossedProcessor();
            } else {
                $processor = new FingersCrossedProcessor();
            }

            yield $stage->build($processor);
        }
    }

    /**
     * @param StageInterface $stage
     * @param array $config
     *
     * @return void
     */
    private function buildSteps(StageInterface $stage, array $config): void
    {
        foreach ($config as $stepConfig) {
            if (!isset($this->plugins[$stepConfig['plugin']])) {
                throw new \RuntimeException(sprintf('Plugin %s was not registered.', $stepConfig['plugin']));
            }

            $stage->add($this->plugins[$stepConfig['plugin']]->buildStep($stepConfig));
        }
    }
}
