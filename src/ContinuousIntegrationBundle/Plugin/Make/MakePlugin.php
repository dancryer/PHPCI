<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\Make;

use Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\PluginConfigurationInterface;
use Kiboko\Bundle\ContinuousIntegrationBundle\Plugin\PluginInterface;

class MakePlugin implements PluginInterface
{
    /**
     * @return PluginConfigurationInterface
     */
    public function getConfiguration(): PluginConfigurationInterface
    {
        return new Configuration();
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return 'make';
    }
}
