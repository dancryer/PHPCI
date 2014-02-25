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
 * TYPO3 CMS extension installation plugin
 *
 * Installs a TYPO3 extension into the TYPO3 environment
 * currently installed in the build path.
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
	 * Extension key - the TYPO3 extension key to install
	 *
	 * @var string
	 */
	protected $extension;

	/**
	 * Also install extension's dependencies. If the value is an
	 * array, values are considered paths to repositories and
	 * the branch or tag name to check out, e.g.
	 * https://github.com/username/repository.git@1.2.0 to check
	 * out the repository at tag "1.2.0". SSH urls currently not
	 * supported!
	 *
	 * @var mixed
	 */
	protected $installDependencies = false;

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
		$command = $this->phpci->buildPath . DIRECTORY_SEPARATOR . 'typo3/cli_dispatch.phpsh extbase extension:install ' . $this->extension;
		$success = $this->phpci->executeCommand($command);

		return $success;
	}
}
