<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Logging;

use Psr\Log\LoggerInterface;

/**
 * Base Log Handler
 * @package PHPCI\Logging
 */
class Handler
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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * Register a new log handler.
     * @param LoggerInterface $logger
     */
    public static function register(LoggerInterface $logger = null)
    {
        $handler = new static($logger);

        set_error_handler(array($handler, 'handleError'));
        register_shutdown_function(array($handler, 'handleFatalError'));

        set_exception_handler(array($handler, 'handleException'));
    }

    /**
     * @param integer $level
     * @param string  $message
     * @param string  $file
     * @param integer $line
     *
     * @throws \ErrorException
     */
    public function handleError($level, $message, $file, $line)
    {
        if (error_reporting() & $level) {

            $exception_level = isset($this->levels[$level]) ? $this->levels[$level] : $level;

            throw new \ErrorException(
                sprintf('%s: %s in %s line %d', $exception_level, $message, $file, $line),
                0,
                $level,
                $file,
                $line
            );
        }
    }

    /**
     * @throws \ErrorException
     */
    public function handleFatalError()
    {
        $fatal_error = error_get_last();

        try {
            if (($error = error_get_last()) !== null) {
                $error = new \ErrorException(
                    sprintf(
                        '%s: %s in %s line %d',
                        $fatal_error['type'],
                        $fatal_error['message'],
                        $fatal_error['file'],
                        $fatal_error['line']
                    ),
                    0,
                    $fatal_error['type'],
                    $fatal_error['file'],
                    $fatal_error['line']
                );
                $this->log($error);
            }
        } catch (\Exception $e) {
            $error = new \ErrorException(
                sprintf(
                    '%s: %s in %s line %d',
                    $fatal_error['type'],
                    $fatal_error['message'],
                    $fatal_error['file'],
                    $fatal_error['line']
                ),
                0,
                $fatal_error['type'],
                $fatal_error['file'],
                $fatal_error['line']
            );
            $this->log($error);
        }
    }

    /**
     * @param \Exception $exception
     */
    public function handleException(\Exception $exception)
    {
        $this->log($exception);
    }

    /**
     * Write to the build log.
     * @param \Exception $exception
     */
    protected function log(\Exception $exception)
    {
        if (null !== $this->logger) {

            $message = sprintf(
                '%s: %s (uncaught exception) at %s line %s',
                get_class($exception),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            );

            $this->logger->error($message, array('exception' => $exception));
        }
    }
}
