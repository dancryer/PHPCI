<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI;

use PHPCI\Helper\BuildInterpolator;
use PHPCI\Logging\BuildLogger;
use PHPCI\Model\Build;
use PHPCI\Store\BuildStore;
use PHPCI\Builder;
use PHPCI\CommandExecutor\Factory as CommandExecutorFactory;
use Psr\Log\LoggerInterface;

/**
 * PHPCI Build Runner factory
 *
 * @author   Dan Cryer <dan@block8.co.uk>
 */
class BuilderFactory
{
    /**
     * @var BuildStore
     */
    protected $buildStore;

    /**
     * @var BuildInterpolator
     */
    protected $buildInterpolator;

    /**
     * @var CommandExecutorFactory
     */
    protected $commandExecutorFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        BuildStore $buildStore,
        BuildInterpolator $buildInterpolator,
        CommandExecutorFactory $commandExecutorFactory,
        LoggerInterface $logger
    ) {
        $this->buildStore = $buildStore;
        $this->buildInterpolator = $buildInterpolator;
        $this->commandExecutorFactory = $commandExecutorFactory;
        $this->logger = $logger;
    }

    /**
     * Create Builder from Build.
     *
     * @param  Build            $build
     * @param  LoggerInterface  $logger
     *
     * @return Builder
     */
    public function fromBuild(Build $build, LoggerInterface $logger = null)
    {
        $logger = $logger ?: $this->logger;
        $buildLogger = new BuildLogger($logger, $build);
        $commandExecutor = $this->commandExecutorFactory(
            $buildLogger,
            PHPCI_DIR,
            false,
            false
        );

        return new Builder(
            $build,
            $this->buildStore,
            $buildLogger,
            $this->buildInterpolator,
            $commandExecutor,
            $logger
        );
    }
}
