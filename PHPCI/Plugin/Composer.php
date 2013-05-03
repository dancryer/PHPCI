<?php

namespace PHPCI\Plugin;

class Composer implements \PHPCI\Plugin
{
	protected $directory;
	protected $action;
	protected $phpci;

	public function __construct(\PHPCI\Builder $phpci, array $options = array())
	{
		$this->phpci		= $phpci;
		$this->directory	= isset($options['directory']) ? $options['directory'] : $phpci->buildPath;
		$this->action		= isset($options['action']) ? $options['action'] : 'update';
	}

	public function execute()
	{
		return $this->phpci->executeCommand('composer --working-dir=' . $this->directory . ' ' . $this->action);
	}
}