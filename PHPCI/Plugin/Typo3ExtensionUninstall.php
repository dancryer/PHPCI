<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2013, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         http://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

/**
 * TYPO3 CMS extension uninstall plugin
 *
 * Uninstalls a TYPO3 extension from the TYPO3 environment
 * currently installed into the build path. Use to fx
 * uninstall unwanted core extensions before you run tests.
 *
 * Requires TYPO3 6.2 in order to function.
 *
 * @author       Claus Due <claus@namelesscoder.net>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Typo3ExtensionInstall implements \PHPCI\Plugin
{

	/**
	 * Extension key - the TYPO3 extension key to uninstall
	 *
	 * @var string
	 */
	protected $extension;

	/**
	 * You say it best when you say nothing at all.
	 *
	 * @var
	 */
	protected $verbose = false;

	protected $phpci;

	/**
	 * @var string $command The command to be executed
	 */
	protected $command;

	public function __construct(Builder $phpci, Build $build, array $options = array())
	{
		$this->phpci        = $phpci;

		if (isset($options['extension'])) {
			$this->extension = $options['extension'];
		}

		if (isset($options['verbose'])) {
			$this->verbose = $options['verbose'];
		}

	}

	/**
	 * Runs the shell command.
	 */
	public function execute()
	{
		$command = $this->phpci->buildPath . DIRECTORY_SEPARATOR . 'typo3/cli_dispatch.phpsh extbase extension:uninstall ' . $this->extension;
		$success = $this->phpci->executeCommand($command);

		return $success;
	}
}
