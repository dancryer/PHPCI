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

        $this->setOptions($options);
    }

    /**
     * Configure the plugin.
     *
     * @param array $options
     */
    abstract protected function setOptions(array $options);
}
