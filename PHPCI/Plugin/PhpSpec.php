<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

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
		$curdir = getcwd();
		chdir($this->phpci->buildPath);
		$success = $this->phpci->executeCommand(PHPCI_BIN_DIR . 'phpspec');
		chdir($curdir);
		
		return $success;
	}
}