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
use PHPCI\BuildFactory;

/**
* Home Controller - Displays the PHPCI Dashboard.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class HomeController extends \PHPCI\Controller
{
    /**
     * @var \b8\Store\BuildStore
     */
    protected $buildStore;

    /**
     * @var \b8\Store\ProjectStore
     */
    protected $projectStore;

    public function init()
    {
        $this->buildStore      = b8\Store\Factory::getStore('Build');
        $this->projectStore    = b8\Store\Factory::getStore('Project');
    }

    /**
    * Display PHPCI dashboard:
    */
    public function index()
    {
        $projects       = $this->projectStore->getWhere(array(), 50, 0, array(), array('title' => 'ASC'));

        $summaryBuilds = array();
        foreach ($projects['items'] as $project) {
            $summaryBuilds[$project->getId()] = $this->buildStore->getLatestBuilds($project->getId());
        }

        $summaryView = new b8\View('SummaryTable');
        $summaryView->projects = $projects['items'];
        $summaryView->builds = $summaryBuilds;

        $this->view->builds   = $this->getLatestBuildsHtml();
        $this->view->projects = $projects['items'];
        $this->view->summary  = $summaryView->render();

        $this->config->set('page_title', 'Dashboard');

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
        $builds         = $this->buildStore->getWhere(array(), 5, 0, array(), array('id' => 'DESC'));
        $view           = new b8\View('BuildsTable');

        foreach ($builds['items'] as &$build) {
            $build = BuildFactory::getBuild($build);
        }

        $view->builds   = $builds['items'];

        return $view->render();
    }
}
