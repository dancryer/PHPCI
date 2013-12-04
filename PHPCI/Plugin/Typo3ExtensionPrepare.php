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
 * TYPO3 CMS extension test preparation plugin
 *
 * Prepares the currently checked out code as a TYPO3 extension for testing
 *
 * @author       Claus Due <claus@namelesscoder.net>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Typo3ExtensionPrepare implements \PHPCI\Plugin
{

	/**
	 * Extension key - the TYPO3 extension key to prepare
	 *
	 * @var string
	 */
	protected $extension;

	/**
	 * Where to grab the backed up extension files which were
	 * initially installed in the build base path and backed
	 * up to another folder before installing TYPO3.
	 *
	 * @var string
	 */
	protected $source = 'temp';

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
		} else {
			throw new \Exception('You must specify the extension to prepare, by extension key, as option "extension"');
		}

		if (isset($options['source'])) {
			$this->source = $options['source'];
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
		$base = $this->phpci->buildPath;
		$target = $base . DIRECTORY_SEPARATOR . 'typo3conf/ext/' . $this->extension;
		$source = $base . DIRECTORY_SEPARATOR . $this->source;
		$command = 'mkdir -p ' . escapeshellarg($target);

		$success = $this->phpci->executeCommand($command);

		return $success;
	}
}
