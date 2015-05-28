<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI;

/**
 * Error Handler
 *
 * @package PHPCI\Logging
 */
class ErrorHandler
{
    /**
     * @var array
     */
    protected $levels = array(
        E_WARNING           => 'Warning',
        E_NOTICE            => 'Notice',
        E_USER_ERROR        => 'User Error',
        E_USER_WARNING      => 'User Warning',
        E_USER_NOTICE       => 'User Notice',
        E_STRICT            => 'Runtime Notice',
        E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
        E_DEPRECATED        => 'Deprecated',
        E_USER_DEPRECATED   => 'User Deprecated',
    );

    /**
     * Registers an instance of the error handler to throw ErrorException.
     */
    public static function register()
    {
        $handler = new static();
        set_error_handler(array($handler, 'handleError'));
    }

    /**
     * @param integer $level
     * @param string  $message
     * @param string  $file
     * @param integer $line
     *
     * @throws \ErrorException
     *
     * @internal
     */
    public function handleError($level, $message, $file, $line)
    {
        if (error_reporting() & $level === 0) {
            return;
        }

        $exceptionLevel = isset($this->levels[$level]) ? $this->levels[$level] : $level;
        throw new \ErrorException(
            sprintf('%s: %s in %s line %d', $exceptionLevel, $message, $file, $line),
            0,
            $level,
            $file,
            $line
        );
    }
}
