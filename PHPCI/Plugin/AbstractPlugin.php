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
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Asbtract plugin.
 *
 * Holds helper for the subclasses.
 */
abstract class AbstractPlugin implements Plugin, LoggerAwareInterface
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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Setup and configure the plugin.
     *
     * @param Builder $builder
     * @param Build   $build
     * @param BuildLogger $logger
     */
    public function __construct(Builder $builder, Build $build)
    {
        $this->phpci = $builder;
        $this->build = $build;

        $this->setBuildPath($builder->buildPath);
        $this->setIgnorePath((array) $builder->ignore);
    }

    /**
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Set the build path.
     *
     * @param string $buildPath
     */
    protected function setBuildPath($buildPath)
    {
        $this->buildPath = $buildPath;
    }

    /**
     * Set the paths to ignore.
     *
     * @param string[] $paths
     */
    protected function setIgnorePaths(array $paths)
    {
        $this->ignore = $paths;
    }

    /**
     * Configure the plugin with the common settings.
     *
     * @param array $settings
     *
     * @SuppressWarnings(unused)
     */
    protected function setCommonSettings(array $settings)
    {
        // NOOP
    }
}
