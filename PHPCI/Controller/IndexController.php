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
class IndexController extends \PHPCI\Controller
{
    public function init()
    {
        $this->_buildStore      = b8\Store\Factory::getStore('Build');
        $this->_projectStore    = b8\Store\Factory::getStore('Project');
    }

    /**
    * Display PHPCI dashboard:
    */
    public function index()
    {
        $projects       = $this->_projectStore->getWhere(array(), 50, 0, array(), array('title' => 'ASC'));
        $this->view->builds   = $this->getLatestBuildsHtml();
        $this->view->projects = $projects['items'];

        return $this->view->render();
    }

    /**
    * AJAX get latest builds table (HTML)
    */
    public function latest()
    {
        die($this->getLatestBuildsHtml());
    }

    /**
    * Get latest builds and render as a table.
    */
    protected function getLatestBuildsHtml()
    {
        $builds         = $this->_buildStore->getWhere(array(), 10, 0, array(), array('id' => 'DESC'));
        $view           = new b8\View('BuildsTable');
        $view->builds   = $builds['items'];

        return $view->render();
    }
}
