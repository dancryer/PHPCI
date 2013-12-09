<?php
namespace PHPCI;

use Psr\Log\LogLevel;

/**
 * PHPCI Build Logger
 */
interface BuildLogger
{
    /**
     * Add an entry to the build log.
     * @param string|string[] $message
     * @param string $level
     * @param mixed[] $context
     */
    public function log($message, $level = LogLevel::INFO, $context = array());

    /**
     * Add a success-coloured message to the log.
     * @param string
     */
    public function logSuccess($message);

    /**
     * Add a failure-coloured message to the log.
     * @param string $message
     * @param \Exception $exception The exception that caused the error.
     */
    public function logFailure($message, \Exception $exception = null);
}