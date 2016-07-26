<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */
namespace PHPCI\Controller;

use b8;
use PHPCI\BuildFactory;
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;

/**
 * Home Controller - Displays the PHPCI Dashboard.
 *
 * @author       Dan Cryer <dan@block8.co.uk>
 */
class HomeController extends \PHPCI\Controller
{
    /**
     * @type \PHPCI\Store\BuildStore
     */
    protected $buildStore;

    /**
     * @type \PHPCI\Store\ProjectStore
     */
    protected $projectStore;

    /**
     * @type \PHPCI\Store\ProjectGroupStore
     */
    protected $groupStore;

    /**
     * Initialise the controller, set up stores and services.
     */
    public function init()
    {
        $this->buildStore      = b8\Store\Factory::getStore('Build');
        $this->projectStore    = b8\Store\Factory::getStore('Project');
        $this->groupStore      = b8\Store\Factory::getStore('ProjectGroup');
    }

    /**
     * Display PHPCI dashboard:
     */
    public function index()
    {
        $this->layout->title = Lang::get('dashboard');
        $builds              = $this->buildStore->getLatestBuilds(null, 10);

        foreach ($builds as &$build) {
            $build = BuildFactory::getBuild($build);
        }

        $this->view->builds   = $builds;
        $this->view->groups   = $this->getGroupInfo();

        return $this->view->render();
    }

    /**
     * AJAX get latest builds table (HTML)
     */
    public function latest()
    {
        $this->response->disableLayout();
        $this->response->setContent($this->getLatestBuildsHtml());

        return $this->response;
    }

    /**
     * Ajax request for the project overview section of the dashboard.
     */
    public function summary()
    {
        $this->response->disableLayout();
        $projects = $this->projectStore->getWhere([], 50, 0, [], ['title' => 'ASC']);
        $this->response->setContent($this->getSummaryHtml($projects));

        return $this->response;
    }

    /**
     * Generate the HTML for the project overview section of the dashboard.
     *
     * @param $projects
     *
     * @return string
     */
    protected function getSummaryHtml($projects)
    {
        $summaryBuilds = [];
        $successes     = [];
        $failures      = [];
        $counts        = [];

        foreach ($projects as $project) {
            $summaryBuilds[$project->getId()] = $this->buildStore->getLatestBuilds($project->getId());

            $count = $this->buildStore->getWhere(
                ['project_id' => $project->getId()],
                1,
                0,
                [],
                ['id' => 'DESC']
            );
            $counts[$project->getId()] = $count['count'];

            $success = $this->buildStore->getLastBuildByStatus($project->getId(), Build::STATUS_SUCCESS);
            $failure = $this->buildStore->getLastBuildByStatus($project->getId(), Build::STATUS_FAILED);

            $successes[$project->getId()] = $success;
            $failures[$project->getId()]  = $failure;
        }

        $summaryView             = new b8\View('SummaryTable');
        $summaryView->projects   = $projects;
        $summaryView->builds     = $summaryBuilds;
        $summaryView->successful = $successes;
        $summaryView->failed     = $failures;
        $summaryView->counts     = $counts;

        return $summaryView->render();
    }

    /**
     * Get latest builds and render as a table.
     */
    protected function getLatestBuildsHtml()
    {
        $builds         = $this->buildStore->getWhere([], 5, 0, [], ['id' => 'DESC']);
        $view           = new b8\View('BuildsTable');

        foreach ($builds['items'] as &$build) {
            $build = BuildFactory::getBuild($build);
        }

        $view->builds   = $builds['items'];

        return $view->render();
    }

    /**
     * Get a summary of the project groups we have, and what projects they have in them.
     *
     * @return array
     */
    protected function getGroupInfo()
    {
        $rtn    = [];
        $groups = $this->groupStore->getWhere([], 100, 0, [], ['title' => 'ASC']);

        foreach ($groups['items'] as $group) {
            $thisGroup             = ['title' => $group->getTitle()];
            $projects              = $this->projectStore->getByGroupId($group->getId());
            $thisGroup['projects'] = $projects['items'];
            $thisGroup['summary']  = $this->getSummaryHtml($thisGroup['projects']);
            $rtn[]                 = $thisGroup;
        }

        return $rtn;
    }
}
