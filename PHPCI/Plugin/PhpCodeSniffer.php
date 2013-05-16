<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;

class PhpCodeSniffer implements \PHPCI\Plugin
{
	protected $directory;
	protected $args;
	protected $phpci;

	public function __construct(\PHPCI\Builder $phpci, array $options = array())
	{
		$this->phpci		= $phpci;
		$this->directory	= isset($options['directory']) ? $options['directory'] : $phpci->buildPath;
		$this->standard		= isset($options['standard']) ? $options['standard'] : 'PSR2';
	}

	public function execute()
	{
		$ignore = '';
		
		if(count($this->phpci->ignore)) {
			$ignore = array_map(function($item)
			{
				return substr($item, -1) == '/' ? $item . '*' : $item . '/*';
			}, $this->phpci->ignore);

			$ignore = ' --ignore=' . implode(',', $ignore);
		}

		return $this->phpci->executeCommand(PHPCI_BIN_DIR . 'phpcs --standard=%s %s "%s"', $this->standard, $ignore, $this->phpci->buildPath);
	}
}