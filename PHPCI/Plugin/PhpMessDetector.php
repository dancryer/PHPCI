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
		if(count($this->phpci->ignore))
		{
			$ignore = array_map(function($item)
			{
				return substr($item, -1) == '/' ? $item . '*' : $item . '/*';
			}, $this->phpci->ignore);

			$ignore = ' --exclude ' . implode(',', $ignore);
		}

		return $this->phpci->executeCommand(PHPCI_BIN_DIR . 'phpmd ' . $this->phpci->buildPath . ' text codesize,unusedcode,naming' . $ignore);
	}
}