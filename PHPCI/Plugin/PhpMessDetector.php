<?php

namespace PHPCI\Plugin;

class PhpMessDetector implements \PHPCI\Plugin
{
	protected $directory;

	public function __construct(\PHPCI\Builder $phpci, array $options = array())
	{
		$this->phpci		= $phpci;
	}

	public function execute()
	{
		$ignore = '';
		
		if(count($this->phpci->ignore))
		{
			$ignore = array_map(function($item)
			{
				return substr($item, -1) == '/' ? $item . '*' : $item . '/*';
			}, $this->phpci->ignore);

			$ignore = ' --exclude ' . implode(',', $ignore);
		}

		return $this->phpci->executeCommand(PHPCI_BIN_DIR . 'phpmd "%s" text codesize,unusedcode,naming %s', $this->phpci->buildPath, $ignore);
	}
}