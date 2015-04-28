<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class OutputLogHandler outputs the build log to the terminal.
 * @package PHPCI\Logging
 */
class OutputLogHandler extends AbstractProcessingHandler
{
    /**
     * Map verbosity levels to log levels.
     *
     * @var int[]
     */
    static protected $levels = array(
        OutputInterface::VERBOSITY_QUIET        => Logger::ERROR,
        OutputInterface::VERBOSITY_NORMAL       => Logger::WARNING,
        OutputInterface::VERBOSITY_VERBOSE      => Logger::NOTICE,
        OutputInterface::VERBOSITY_VERY_VERBOSE => Logger::INFO,
        OutputInterface::VERBOSITY_DEBUG        => Logger::DEBUG,
    );

     * @var OutputInterface
     */
    protected $output;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        parent::__construct(static::$levels[$output->getVerbosity()]);
        $this->output = $output;
    }

    /**
     * Write a log entry to the terminal.
     * @param array $record
     */
    protected function write(array $record)
    {
        if ($record['level'] >= Logger::ERROR && $this->output instanceof ConsoleOutputInterface) {
            $output = $this->output->getErrorOutput();
        } else {
            $output = $this->output;
        }

        $output->write($record['formatted']);
    }
}
