<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Logging;

use PHPCI\Model\Build;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class BuildLogger
 * @package PHPCI\Logging
 */
class BuildLogger implements LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Build
     */
    protected $build;

    /**
     * Set up the BuildLogger class.
     * @param LoggerInterface $logger
     * @param Build $build
     */
    public function __construct(LoggerInterface $logger, Build $build)
    {
        $this->logger = $logger;
        $this->build = $build;
    }

    /**
     * Add an entry to the build log.
     * @param string|string[] $message
     * @param string $level
     * @param mixed[] $context
     */
    public function log($message, $level = LogLevel::INFO, $context = array())
    {
        // Skip if no logger has been loaded.
        if (!$this->logger) {
            return;
        }

        if (!is_array($message)) {
            $message = array($message);
        }

        // The build is added to the context so the logger can use
        // details from it if required.
        $context['build'] = $this->build;

        foreach ($message as $item) {
            $this->logger->log($level, $item, $context);
        }
    }

   /**
     * Add a success-coloured message to the log.
     * @param string
     */
    public function logSuccess($message)
    {
        $this->log("\033[0;32m" . $message . "\033[0m");
    }

    /**
     * Add a failure-coloured message to the log.
     * @param string $message
     * @param \Exception $exception The exception that caused the error.
     */
    public function logFailure($message, \Exception $exception = null)
    {
        $context = array();

        // The psr3 log interface stipulates that exceptions should be passed
        // as the exception key in the context array.
        if ($exception) {
            $context['exception'] = $exception;
        }

        $this->log(
            "\033[0;31m" . $message . "\033[0m",
            LogLevel::ERROR,
            $context
        );
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
