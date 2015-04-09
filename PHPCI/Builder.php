<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI;

use Exception;
use Psr\Log\LogLevel;

/**
 * PHPCI Build Runner
 * @author   Dan Cryer <dan@block8.co.uk>
 */
class Builder extends BaseBuilder
{
    /**
     * Used by this class, and plugins, to execute shell commands.
     */
    public function executeCommand()
    {
        return $this->commandExecutor->executeCommand(func_get_args());
    }

    /**
     * Returns the output from the last command run.
     */
    public function getLastOutput()
    {
        return $this->commandExecutor->getLastOutput();
    }

    /**
     * Specify whether exec output should be logged.
     * @param bool $enableLog
     */
    public function logExecOutput($enableLog = true)
    {
        $this->commandExecutor->logExecOutput = $enableLog;
    }

    /**
     * Find a binary required by a plugin.
     * @param $binary
     * @return null|string
     */
    public function findBinary($binary)
    {
        return $this->commandExecutor->findBinary($binary, $this->buildPath);
    }

    /**
     * Replace every occurrence of the interpolation vars in the given string
     * Example: "This is build %PHPCI_BUILD%" => "This is build 182"
     * @param string $input
     * @return string
     */
    public function interpolate($input)
    {
        return $this->interpolator->interpolate($input);
    }

    /**
     * Write to the build log.
     * @param $message
     * @param string $level
     * @param array $context
     */
    public function log($message, $level = LogLevel::INFO, $context = array())
    {
        $this->buildLogger->log($message, $level, $context);
    }

    /**
     * Add a success-coloured message to the log.
     * @param string
     */
    public function logSuccess($message)
    {
        $this->buildLogger->logSuccess($message);
    }

    /**
     * Add a failure-coloured message to the log.
     * @param string $message
     * @param Exception $exception The exception that caused the error.
     */
    public function logFailure($message, Exception $exception = null)
    {
        $this->buildLogger->logFailure($message, $exception);
    }
}
