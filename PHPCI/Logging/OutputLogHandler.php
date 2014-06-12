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
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\OutputInterface;

class OutputLogHandler extends AbstractProcessingHandler
{
    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(
        OutputInterface $output,
        $level = LogLevel::INFO,
        $bubble = true
    ) {
        parent::__construct($level, $bubble);
        $this->output = $output;
    }

    protected function write(array $record)
    {
        $this->output->writeln((string)$record['formatted']);
    }
}
