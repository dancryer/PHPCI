<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI;

use b8\Store\Factory;
use Monolog\Handler\PsrHandler;
use Monolog\Logger;
use PHPCI\Logging\BuildDBLogHandler;
use PHPCI\Model\Build;
use PHPCI\Store\BuildStore;
use Psr\Log\LoggerInterface;

/**
 * PHPCI Build Runner
 *
 * @author   Adirelle <adirelle@gmail.com>
 */
class BuilderFactory
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var BuildStore
     */
    protected $store;

    /** Initialize the factory.
     *
     * @param LoggerInterface $logger
     * @param BuildStore $store
     */
    public function __construct(LoggerInterface $logger, BuildStore $store = null)
    {
        $this->logger = $logger;
        $this->store = $store ? $store : Factory::get('Build');
    }

    /**
     * Create a Builder suitable for the given Build.
     *
     * @param Build $build
     *
     * @return Builder
     *
     * @todo Move most of the Builder setup here
     */
    public function createBuilder(Build $build)
    {
        return new Builder($build, $this->createBuildLogger($build));
    }

    /**
     * Create a logger that forwards the logs to both the outer logger and a build-specific handler.
     *
     * Also provide a processor that adds useful information to the context.
     *
     * @param Build $build
     *
     * @return LoggerInterface
     */
    protected function createBuildLogger(Build $build)
    {
        return new Logger(
            "Builder-" . $build->getId(),
            // Handlers
            array(
                new PsrHandler($this->logger),
                new BuildDBLogHandler($build, $this->store)
            ),
            // Processors
            array(
                // A simple processor that adds useful information to the context
                function ($record) use ($build) {
                    $record['context']['buildID'] = $build->getId();
                    $record['context']['elapsed'] = time() - $build->getStarted()->getTimestamp();
                    return $record;
                }
            )
        );
    }
}
