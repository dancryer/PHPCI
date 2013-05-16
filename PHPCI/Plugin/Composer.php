<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;

/**
* Composer Plugin - Provides access to Composer functionality.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class Composer implements \PHPCI\Plugin
{
	protected $directory;
	protected $action;
	protected $phpci;

	public function __construct(\PHPCI\Builder $phpci, array $options = array())
	{
		$this->phpci		= $phpci;
		$this->directory	= isset($options['directory']) ? $phpci->buildPath . '/' . $options['directory'] : $phpci->buildPath;
		$this->action		= isset($options['action']) ? $options['action'] : 'update';
	}

	public function execute()
	{
		return $this->phpci->executeCommand(PHPCI_DIR . 'composer.phar --prefer-dist --working-dir="%s" %s', $this->directory, $this->action);
	}
}