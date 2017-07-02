<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Plugin;

use Kiboko\Bundle\ContinuousIntegrationBundle\Pipeline\StepInterface;

interface PluginInterface
{
    /**
     * @return PluginConfigurationInterface
     */
    public function getConfiguration(): PluginConfigurationInterface;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @param array $stepConfig
     *
     * @return StepInterface
     */
    public function buildStep(array $stepConfig): StepInterface;
}
