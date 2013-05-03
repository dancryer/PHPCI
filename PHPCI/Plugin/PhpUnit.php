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
		return $this->phpci->executeCommand('phpunit ' . $this->args . ' ' . $this->phpci->buildPath . $this->directory);
	}
}