<?php

namespace PHPCI\Helper;


use b8\Store;
use Monolog\Handler\AbstractProcessingHandler;
use PHPCI\Model\Build;

class BuildDBLogHandler extends AbstractProcessingHandler
{
	/**
	 * @var Build
	 */
	protected $build;

	protected $logValue;

	function __construct(Build $build,
						 $level = LogLevel::INFO,
						 $bubble = true)
	{
		parent::__construct($level, $bubble);
		$this->build = $build;
		// We want to add to any existing saved log information.
		$this->logValue = $build->getLog();
	}

	protected function write(array $record) {
		$this->logValue .= (string) $record['formatted'];
		$this->build->setLog($this->logValue);
	}
} 