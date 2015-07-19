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
use PHPCI\Helper\Lang;
use PHPCI\Helper\MailerFactory;
use PHPCI\Logging\BuildLogger;
use PHPCI\Model\Build;
use PHPCI\Store\BuildStore;
use PHPCI\Builder;
use PHPCI\CommandExecutor\Factory as CommandExecutorFactory;
use PHPCI\Config;
use b8\Store\Factory;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use PHPCI\Plugin\Util\Factory as PluginFactory;

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
        $this->logger = $logger;
        $this->buildInterpolator = $buildInterpolator;
        $this->commandExecutorFactory = $commandExecutorFactory;
    }

    public function fromBuild($build, $logger = null)
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
