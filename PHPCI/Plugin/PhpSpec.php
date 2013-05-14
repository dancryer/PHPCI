<?php

namespace PHPCI\Plugin;

class PhpSpec implements \PHPCI\Plugin
{
	protected $phpci;

	public function __construct(\PHPCI\Builder $phpci, array $options = array())
	{
		$this->phpci		= $phpci;
	}

	public function execute()
	{
		$cwd = getcwd();

		$command = 'cd ' . $this->phpci->buildPath . ' && ';
		$command .= PHPCI_BIN_DIR . 'phpspec';
		$command .= ' && cd ' . $cwd;
		return $this->phpci->executeCommand($command);
	}
}