<?php

namespace PHPCI\Plugin;

class PhpUnit implements \PHPCI\Plugin
{
	protected $directory;
	protected $args;
	protected $phpci;

/**
 * @var string $xmlConfigFile The path of an xml config for PHPUnit
 */
	protected $xmlConfigFile;

	public function __construct(\PHPCI\Builder $phpci, array $options = array())
	{
		$this->phpci		= $phpci;
		$this->directory	= isset($options['directory']) ? $options['directory'] : $phpci->buildPath;
		$this->xmlConfigFile = isset($options['config']) ? $options['config'] : null;
		$this->args			= isset($options['args']) ? $options['args'] : '';
	}

	public function execute()
	{
		if ($this->xmlConfigFile === null) {
			$curdir = getcwd();
			chdir($this->phpci->buildPath);
			$success = $this->phpci->executeCommand(PHPCI_BIN_DIR . 'phpunit ' . $this->args . ' ' . $this->phpci->buildPath . $this->directory);
			chdir($curdir);
		}
		else {
			$success = $this->phpci->executeCommand(PHPCI_BIN_DIR . 'phpunit ' . $this->args . ' -c ' . $this->phpci->buildPath . $this->xmlConfigFile );
		}
		
		return $success;
	}
}