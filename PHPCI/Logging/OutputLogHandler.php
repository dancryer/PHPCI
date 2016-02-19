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
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class OutputLogHandler outputs the build log to the terminal.
 *
 * @package PHPCI\Logging
 */
class OutputLogHandler extends AbstractProcessingHandler
{
    /**
     * @var array
     */
    protected static $levels = array(
        OutputInterface::VERBOSITY_QUIET => Logger::ERROR,
        OutputInterface::VERBOSITY_NORMAL => Logger::WARNING,
        OutputInterface::VERBOSITY_VERBOSE => Logger::NOTICE,
        OutputInterface::VERBOSITY_VERY_VERBOSE => Logger::INFO,
        OutputInterface::VERBOSITY_DEBUG => Logger::DEBUG,
    );
    /**
     * @var array
     */
    protected static $colors = array(
        Logger::ERROR => 'red',
        Logger::WARNING => 'yellow',
        Logger::NOTICE => 'green',
        Logger::INFO => 'white'
    );
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * OutputLogHandler constructor.
     *
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        parent::__construct(static::$levels[$output->getVerbosity()]);
        $this->output = $output;
        $this->pushProcessor(array($this, 'addConsoleColor'));

    }

    public function addConsoleColor($record)
    {
        foreach (static::$colors as $level => $color) {
            if ($record['level'] >= $level) {
                break;
            }

        }

        $record['message'] = sprintf('<fg=%s>%s</fg=%s>', $color, rtrim($record['message']), $color);

        return $record;
    }

    /**
     * Write a log entry to the terminal.
     *
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
