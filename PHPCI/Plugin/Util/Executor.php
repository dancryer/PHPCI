<?php

namespace PHPCI\Plugin\Util;

use PHPCI\Helper\Lang;
use \PHPCI\Logging\BuildLogger;

/**
 * Plugin Executor - Runs the configured plugins for a given build stage.
 * @package PHPCI\Plugin\Util
 */
class Executor
{
    /**
     * @var BuildLogger
     */
    protected $logger;

    /**
     * @var Factory
     */
    protected $pluginFactory;

    /**
     * @param Factory $pluginFactory
     * @param BuildLogger $logger
     */
    public function __construct(Factory $pluginFactory, BuildLogger $logger)
    {
        $this->pluginFactory = $pluginFactory;
        $this->logger = $logger;
    }

    /**
     * Execute a the appropriate set of plugins for a given build stage.
     * @param array $config PHPCI configuration
     * @param string $stage
     * @return bool
     */
    public function executePlugins(&$config, $stage)
    {
        $success = true;
        // Ignore any stages for which we don't have plugins set:
        if (!array_key_exists($stage, $config) || !is_array($config[$stage])) {
            return $success;
        }

        foreach ($config[$stage] as $plugin => $options) {
            $this->logger->log(Lang::get('running_plugin', $plugin));

            $settings = isset($config['build_settings'][$plugin]) ? $config['build_settings'][$plugin] : array();

            // Try and execute it:
            if ($this->executePlugin($plugin, $options, $settings)) {
                // Execution was successful:
                $this->logger->logSuccess(Lang::get('plugin_success'));
            } elseif ($stage == 'setup') {
                // If we're in the "setup" stage, execution should not continue after
                // a plugin has failed:
                throw new \Exception('Plugin failed: ' . $plugin);
            } else {
                // If we're in the "test" stage and the plugin is not allowed to fail,
                // then mark the build as failed:
                if ($stage == 'test' && (!isset($options['allow_failures']) || !$options['allow_failures'])) {
                    $success = false;
                }

                $this->logger->logFailure(Lang::get('plugin_failed'));
            }
        }

        return $success;
    }

    /**
     * Executes a given plugin, with options and returns the result.
     *
     * @param string $plugin Plugin name or class.
     * @param array $options Stage options.
     * @param array $settings Common options.
     * @return boolean true if plugin ended successfully, false if something went wrong.
     */
    public function executePlugin($plugin, array $options, array $settings)
    {
        try {
            $obj = $this->buildPlugin($plugin);
            $obj->setCommonSettings($settings);
            $obj->setOptions($options);

            return $obj->execute();
        } catch (\Exception $ex) {
            $this->logger->logFailure(Lang::get('exception') . $ex->getMessage(), $ex);
            return false;
        }
    }

    /**
     * Create a plugin instance of the given name.
     *
     * @param string $plugin Plugin class or name
     * @return \PHPCI\Plugin
     * @throws \Exception If the plugin could not be created.
     */
    protected function buildPlugin($plugin)
    {
        // Any plugin name without a namespace separator is a PHPCI built in plugin
        // if not we assume it's a fully name-spaced class name that implements the plugin interface.
        // If not the factory will throw an exception.
        if (strpos($plugin, '\\') === false) {
            $class = str_replace('_', ' ', $plugin);
            $class = ucwords($class);
            $class = 'PHPCI\\Plugin\\' . str_replace(' ', '', $class);
        } else {
            $class = $plugin;
        }

        if (!class_exists($class)) {
            throw new \Exception(Lang::get('plugin_missing', $plugin));
        }

        return $this->pluginFactory->buildPlugin($class);
    }
}
