<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Logging;

use b8\Store\Factory;
use Monolog\Handler\AbstractProcessingHandler;
use PHPCI\Model\Build;
use Psr\Log\LogLevel;

/**
 * Class BuildDBLogHandler writes the build log to the database.
 * @package PHPCI\Logging
 */
class BuildDBLogHandler extends AbstractProcessingHandler
{
    /**
     * @var Build
     */
    protected $build;

    protected $logValue;

    /**
     * @param Build $build
     * @param bool $level
     * @param bool $bubble
     */
    public function __construct(
        Build $build,
        $level = LogLevel::INFO,
        $bubble = true
    ) {
        parent::__construct($level, $bubble);
        $this->build = $build;
        // We want to add to any existing saved log information.
        $this->logValue = $build->getLog();
    }

    /**
     * Write a log entry to the build log.
     * @param array $record
     */
    protected function write(array $record)
    {
        $message = (string)$record['message'];
        $message = str_replace($this->build->currentBuildPath, '/', $message);

        $this->logValue .= $message . PHP_EOL;
        $this->build->setLog($this->logValue);

        Factory::getStore('Build')->save($this->build);
    }
}
