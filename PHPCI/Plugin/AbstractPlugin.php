<?php
/**
 * PHPCI - Continuous Integration for PHP.
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Logging\BuildLogger;
use PHPCI\Model\Build;
use PHPCI\Plugin;

/**
 * Asbtract plugin.
 *
 * Holds helper for the subclasses.
 */
abstract class AbstractPlugin implements Plugin
{
    /**
     * @var Build
     */
    protected $build;

    /**
     * @var Builder
     */
    protected $phpci;

    /**
     * @var BuildLogger
     */
    protected $logger;

    /**
     * Setup and configure the plugin.
     *
     * @param Builder $builder
     * @param Build   $build
     * @param BuildLogger $logger
     * @param array   $options
     */
    public function __construct(Builder $builder, Build $build, BuildLogger $logger, array $options = array())
    {
        $this->phpci = $builder;
        $this->build = $build;
        $this->buildPath = $builder->buildPath;
        $this->logger = $logger;

        $this->setup($options);
    }

    /**
     * Return the key used for options in phpci.yml for this plugin.
     *
     * @return string
     */
    public function getPluginKey()
    {
        $matches = null;
        $className = get_class($this);
        if (!preg_match('/^PHPCI\\Plguin\\(\w+)$/', $className, $matches)) {
            return $className;
        }
        return strtolower(preg_replace('/[[:lower:]][[:upper:]]/g', '$1_$2', $matches[1]));
    }

    /**
     * Configure the plugin with the common settings.
     *
     * @param array $settings
     */
    protected function setCommonSettings(array $settings)
    {
        // NOOP
    }

    /**
     * Configure the plugin for a given stage.
     *
     * @param array $options
     */
    abstract protected function setOptions(array $options);

    /**
     * Post-constructor setup.
     *
     * TODO: expose setOptions and setCommonSettings in Plugin and get rid of this method.
     *
     * @param array $options
     */
    private function setup(array $options)
    {
        $settings = $this->phpci->getConfig('build_settings');
        $key = $this->getPluginKey();
        if ($settings && isset($settings[$key])) {
            $this->setCommonSettings((array) $settings[$key]);
        } else {
            $this->setCommonSettings(array());
        }

        $this->setOptions($options);
    }
}
