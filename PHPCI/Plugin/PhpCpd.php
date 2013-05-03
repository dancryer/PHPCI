<?php

namespace PHPCI\Plugin;

class PhpCpd implements \PHPCI\Plugin
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
		if(count($this->phpci->ignore))
		{
			$ignore = array_map(function($item)
			{
				return ' --exclude ' . (substr($item, -1) == '/' ? $item . '' : $item . '/');
			}, $this->phpci->ignore);

			$ignore = ' ' . implode('', $ignore);
		}

		return $this->phpci->executeCommand('phpcpd ' . $ignore . ' ' . $this->phpci->buildPath);
	}
}