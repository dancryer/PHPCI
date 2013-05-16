<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Controller;
use b8;

/**
* Index Controller - Displays the PHPCI Dashboard.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class IndexController extends b8\Controller
{
	public function init()
	{
		$this->_buildStore		= b8\Store\Factory::getStore('Build');
		$this->_projectStore	= b8\Store\Factory::getStore('Project');
	}

	public function index()
	{
		$projects		= $this->_projectStore->getWhere(array(), 50, 0, array(), array('title' => 'ASC'));
		$view			= new b8\View('Index');
		$view->builds	= $this->getLatestBuildsHtml();
		$view->projects	= $projects['items'];

		return $view->render();
	}

	public function latest()
	{
		die($this->getLatestBuildsHtml());
	}

	protected function getLatestBuildsHtml()
	{
		$builds			= $this->_buildStore->getWhere(array(), 10, 0, array(), array('id' => 'DESC'));
		$view			= new b8\View('BuildsTable');
		$view->builds	= $builds['items'];

		return $view->render();
	}
}