<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Plugin;

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
}
