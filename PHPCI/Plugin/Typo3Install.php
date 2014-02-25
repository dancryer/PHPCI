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
 * TYPO3 CMS install plugin
 *
 * Prepares a TYPO3 environment in the build folder. Please note: if you are testing
 * TYPO3 plugins, make sure to first create a backup copy using fx the CopyBuild plugin.
 *
 * @author       Claus Due <claus@namelesscoder.net>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Typo3Install implements \PHPCI\Plugin
{

	/**
	 * TYPO3 core version to install. Can be either a specific version
	 * number or "master" to use Git HEAD. When specifying a specific
	 * version, format must be fx "6.1.5" or "6.2.0beta2"; in other words
	 * you cannot use values like "6.1" for most recent bugfix version.
	 *
	 * @var mixed
	 */
	protected $version = 'master';

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
		$this->phpci = $phpci;

		if (isset($options['version'])) {
			$this->version = $options['version'];
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
		$command = 'git clone https://github.com/TYPO3/TYPO3.CMS.git .';
		$success = $this->phpci->executeCommand($command);
		if ($success && $this->version !== 'master') {
			$version = str_replace('.', '-', $this->version);
			$command = 'git checkout -b TYPO3_' . $version;
			if (!$this->phpci->executeCommand($command)) {
				throw new \Exception('Could not check out TYPO3 version ' . $version);
			}
		}

		if ($success) {
			$dumpFile = 'https://raw.github.com/FluidTYPO3/Introduction/master/typo3conf/ext/introduction/Resources/Private/Subpackages/Introduction/Database/introduction.sql';
			$dump = file_get_contents($dumpFile);
			$link = mysql_connect('localhost', 'test', NULL, TRUE);
			mysql_select_db('test', $link);
			$inserted = mysql_query($dump);
			if (!$inserted) {
				throw new \Exception('Unable to install TYPO3 introduction package SQL');
			}
		}

		return $success;
	}
}
