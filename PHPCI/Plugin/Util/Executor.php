<?php

namespace PHPCI\Plugin\Util;

use PHPCI\Builder;

class Executor
{

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var Factory
     */
    protected $pluginFactory;

    function __construct(Factory $pluginFactory, Builder $builder)
    {
        $this->pluginFactory = $pluginFactory;
        $this->builder = $builder;
    }

    /**
     * Execute a the appropriate set of plugins for a given build stage.
     * @param array $config PHPCI configuration
     * @param string $stage
     */
    public function executePlugins(&$config, $stage)
    {
        // Ignore any stages for which we don't have plugins set:
        if (!array_key_exists($stage, $config) || !is_array($config[$stage])) {
            return;
        }

        foreach ($config[$stage] as $plugin => $options) {
            $this->builder->log('RUNNING PLUGIN: ' . $plugin);

            // Is this plugin allowed to fail?
            if ($stage == 'test' && !isset($options['allow_failures'])) {
                $options['allow_failures'] = false;
            }

            // Try and execute it:
            if ($this->executePlugin($plugin, $options)) {

                // Execution was successful:
                $this->builder->logSuccess('PLUGIN STATUS: SUCCESS!');

            } else {

                // If we're in the "test" stage and the plugin is not allowed to fail,
                // then mark the build as failed:
                if ($stage == 'test' && !$options['allow_failures']) {
                    $this->success = false;
                }

                $this->builder->logFailure('PLUGIN STATUS: FAILED');
            }
        }
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
        }
        else {
            $class = $plugin;
        }

        if (!class_exists($class)) {
            $this->builder->logFailure('Plugin does not exist: ' . $plugin);
            return false;
        }

        $rtn = true;

        // Try running it:
        try {
            $obj = $this->pluginFactory->buildPlugin($class, $options);

            if (!$obj->execute()) {
                $rtn = false;
            }
        } catch (\Exception $ex) {
            $this->builder->logFailure('EXCEPTION: ' . $ex->getMessage(), $ex);
            $rtn = false;
        }

        return $rtn;
    }

} 