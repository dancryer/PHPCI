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
use PHPCI\Helper\CommandExecutor;
use PHPCI\Logging\BuildLogger;
use PHPCI\Model\Build;

/**
 * Asbtract plugin that executes commands.
 *
 * Holds helper for the subclasses.
 */
abstract class AbstractExecutingPlugin extends AbstractPlugin
{
    /**
     * @var CommandExecutor
     */
    protected $executor;

    /**
     * Setup and configure the plugin.
     *
     * @param Builder $builder
     * @param Build   $build
     * @param BuildLogger $logger
     * @param CommandExecutor $executor
     * @param array   $options
     */
    public function __construct(
        Builder $builder,
        Build $build,
        BuildLogger $logger,
        CommandExecutor $executor,
        array $options = array()
    ) {
        $this->executor = $executor;
        parent:__construct($builder, $build, $logger, $options);
    }
}
