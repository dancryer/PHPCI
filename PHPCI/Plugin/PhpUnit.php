<?php

namespace PHPCI\Plugin;

class PhpUnit implements \PHPCI\Plugin
{
	protected $args;
	protected $phpci;

	/**
	 * @var string|string[] $directory The directory (or array of dirs) to run PHPUnit on
	 */
	protected $directory;
	
	/**
	 * @var string $runFrom When running PHPUnit with an XML config, the command is run from this directory
	 */
	protected $runFrom;

	/**
	 * @var string|string[] $xmlConfigFile The path (or array of paths) of an xml config for PHPUnit
	 */
	protected $xmlConfigFile;

	public function __construct(\PHPCI\Builder $phpci, array $options = array())
	{
		$this->phpci		= $phpci;
		$this->directory	= isset($options['directory']) ? $options['directory'] : null;
		$this->xmlConfigFile = isset($options['config']) ? $options['config'] : null;
		$this->runFrom = isset($options['run_from']) ? $options['run_from'] : null;
		$this->args			= isset($options['args']) ? $options['args'] : '';
	}

	public function execute()
	{
		$success = true;

		// Run any config files first. This can be either a single value or an array.
		if ($this->xmlConfigFile !== null)
		{
			$success &= $this->runConfigFile($this->xmlConfigFile);
		}

		// Run any dirs next. Again this can be either a single value or an array.
		if ($this->directory !== null)
		{
			$success &= $this->runDir($this->directory);
		}
		
		return $success;
	}

	protected function runConfigFile($configPath)
	{
		if (is_array($configPath))
		{
			return $this->recurseArg($configPath, array($this, "runConfigFile"));
		}
		else
		{
			if ($this->runFrom) {
				$curdir = getcwd();
				chdir($this->phpci->buildPath.'/'.$this->runFrom);
			}
			$success = $this->phpci->executeCommand(PHPCI_BIN_DIR . 'phpunit ' . $this->args . ' -c ' . $this->phpci->buildPath . $configPath);
			if ($this->runFrom) {
				chdir($curdir);
			}
			return $success;
		}
	}

	protected function runDir($dirPath)
	{
		if (is_array($dirPath)) {
			return $this->recurseArg($dirPath, array($this, "runConfigFile"));
		}
		else {
			$curdir = getcwd();
			chdir($this->phpci->buildPath);
			$success = $this->phpci->executeCommand(PHPCI_BIN_DIR . 'phpunit ' . $this->args . ' ' . $this->phpci->buildPath . $dirPath);
			chdir($curdir);
			return $success;
		}
	}

	protected function recurseArg($array, $callable) {
		$success = true;
		foreach($array as $subItem) {
			$success &= call_user_func($callable, $subItem);
		}
		return $success;
	}
}