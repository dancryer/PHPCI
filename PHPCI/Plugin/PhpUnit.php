<?php

namespace PHPCI\Plugin;

class PhpUnit implements \PHPCI\Plugin
{
	protected $directory;
	protected $args;
	protected $phpci;

	public function __construct(\PHPCI\Builder $phpci, array $options = array())
	{
		$this->phpci		= $phpci;
		$this->directory	= isset($options['directory']) ? $options['directory'] : $phpci->buildPath;
		$this->args			= isset($options['args']) ? $options['args'] : '';
	}

	public function execute()
	{
		$curdir = getcwd();
		chdir($this->phpci->buildPath);
		$success = $this->phpci->executeCommand(PHPCI_BIN_DIR . 'phpunit ' . $this->args . ' ' . $this->phpci->buildPath . $this->directory);
		chdir($curdir);
		return $success;
	}
}