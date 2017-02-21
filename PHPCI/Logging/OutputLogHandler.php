<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Logging;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
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

    /**
     * Map log levels to colors.
     *
     * @var array
     */
    static protected $colors = array(
        Logger::ERROR =>   'red',
        Logger::WARNING => 'yellow',
        Logger::NOTICE =>  'green',
        // No color markup below NOTICE
        Logger::INFO =>    false
    );

    /**
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
        $this->pushProcessor(array($this, 'addConsoleColor'));
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

    /**
     * Enable the enhancements of the default formatter.
     *
     * @return FormatterInterface
     */
    protected function getDefaultFormatter()
    {
        $formatter = parent::getDefaultFormatter();
        $formatter->ignoreEmptyContextAndExtra(true);
        $formatter->allowInlineLineBreaks(true);
        $formatter->includeStacktraces(true);
        return $formatter;
    }

    /**
     * Add console coloring to the message.
     *
     * @param array $record
     * @return array
     *
     * @internal Used as a Processor.
     */
    public function addConsoleColor($record)
    {
        foreach (static::$colors as $level => $color) {
            if ($record['level'] >= $level) {
                break;
            }
        }

        if ($color !== false) {
            $record['message'] = sprintf('<fg=%s>%s</fg=%s>', $color, rtrim($record['message']), $color);
        }

        return $record;
    }
}
