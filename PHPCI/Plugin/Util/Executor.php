<?php

namespace PHPCI\Plugin\Util;

use b8\Store\Factory as StoreFactory;
use Exception;
use PHPCI\Helper\Lang;
use PHPCI\Logging\BuildLogger;
use PHPCI\Model\Build;
use PHPCI\Store\BuildStore;

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
     * @var BuildStore
     */
    protected $store;

    /**
     * @param Factory $pluginFactory
     * @param BuildLogger $logger
     */
    public function __construct(Factory $pluginFactory, BuildLogger $logger, BuildStore $store = null)
    {
        $this->pluginFactory = $pluginFactory;
        $this->logger = $logger;
        $this->store = $store ?: StoreFactory::getStore('Build');
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

            $this->setPluginStatus($stage, $plugin, Build::STATUS_RUNNING);

            // Try and execute it
            if ($this->executePlugin($plugin, $options)) {
                // Execution was successful
                $this->logger->logSuccess(Lang::get('plugin_success'));
                $this->setPluginStatus($stage, $plugin, Build::STATUS_SUCCESS);
            } else {
                // Execution failed
                $this->logger->logFailure(Lang::get('plugin_failed'));
                $this->setPluginStatus($stage, $plugin, Build::STATUS_FAILED);

                if ($stage === 'setup') {
                    // If we're in the "setup" stage, execution should not continue after
                    // a plugin has failed:
                    throw new Exception('Plugin failed: ' . $plugin);
                } elseif ($stage === 'test') {
                    // If we're in the "test" stage and the plugin is not allowed to fail,
                    // then mark the build as failed:
                    if (empty($options['allow_failures'])) {
                        $success = false;
                    }
                }
            }
        }

        return $success;
    }

    /**
     * Executes a given plugin, with options and returns the result.
     */
    public function executePlugin($plugin, $options)
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
            $this->logger->logFailure(Lang::get('plugin_missing', $plugin));
            return false;
        }

        try {
            // Build and run it
            $obj = $this->pluginFactory->buildPlugin($class, $options);
            return $obj->execute();
        } catch (\Exception $ex) {
            $this->logger->logFailure(Lang::get('exception') . $ex->getMessage(), $ex);
            return false;
        }
    }

    /**
     * Change the status of a plugin for a given stage.
     *
     * @param string $stage The builder stage.
     * @param string $plugin The plugin name.
     * @param int $status The new status.
     */
    protected function setPluginStatus($stage, $plugin, $status)
    {
        $summary = $this->getBuildSummary();

        if (!isset($summary[$stage][$plugin])) {
            $summary[$stage][$plugin] = array();
        }

        $summary[$stage][$plugin]['status'] = $status;

        if ($status === Build::STATUS_RUNNING) {
            $summary[$stage][$plugin]['started'] = time();
        } elseif ($status >= Build::STATUS_SUCCESS) {
            $summary[$stage][$plugin]['ended'] = time();
        }

        $this->setBuildSummary($summary);
    }

    /**
     * Fetch the summary data of the current build.
     *
     * @return array
     */
    private function getBuildSummary()
    {
        $build = $this->pluginFactory->getResourceFor('PHPCI\Model\Build');
        $metas = $this->store->getMeta('plugin-summary', $build->getProjectId(), $build->getId());
        return isset($metas[0]['meta_value']) ? $metas[0]['meta_value'] : array();
    }

    /**
     * Sets the summary data of the current build.
     *
     * @param array summary
     */
    private function setBuildSummary($summary)
    {
        $build = $this->pluginFactory->getResourceFor('PHPCI\Model\Build');
        $this->store->setMeta($build->getProjectId(), $build->getId(), 'plugin-summary', json_encode($summary));
    }
}
