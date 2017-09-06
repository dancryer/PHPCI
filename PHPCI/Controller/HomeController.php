<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Controller;

use PHPCI\Framework;
use PHPCI\BuildFactory;
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;
use PHPCI\Model\BuildCollection;
use PHPCI\Store\BuildStore;
use PHPCI\Store\ProjectGroupStore;
use PHPCI\Store\ProjectStore;

/**
* Home Controller - Displays the PHPCI Dashboard.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class HomeController extends \PHPCI\Controller
{
    /**
     * @var \PHPCI\Store\BuildStore
     */
    protected $buildStore;

    /**
     * @var \PHPCI\Store\ProjectStore
     */
    protected $projectStore;

    /**
     * @var \PHPCI\Store\ProjectGroupStore
     */
    protected $groupStore;

    /**
     * Initialise the controller, set up stores and services.
     */
    public function init()
    {
        $this->buildStore   = BuildStore::load();
        $this->projectStore = ProjectStore::load();
        $this->groupStore   = ProjectGroupStore::load();
    }

    /**
    * Display PHPCI dashboard:
    */
    public function index()
    {
        $this->layout->title = Lang::get('dashboard');
        $buildResults = $this->buildStore->getLatestBuilds(null, 10);
        $builds = [];

        foreach ($buildResults as $build) {
            $builds[] = BuildFactory::getBuild($build);
        }

        $this->view->builds   = $builds;
        $this->view->groups = $this->getGroupInfo();

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
        $projects = $this->projectStore->find()->order('title', 'ASC')->get(50);
        $this->response->setContent($this->getSummaryHtml($projects));
        return $this->response;
    }

    /**
     * Generate the HTML for the project overview section of the dashboard.
     * @param $projects
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

            $counts[$project->getId()] = $this->buildStore->where('project_id', $project->getId())->count();

            $success = $this->buildStore->getLastBuildByStatus($project->getId(), Build::STATUS_SUCCESS);
            $failure = $this->buildStore->getLastBuildByStatus($project->getId(), Build::STATUS_FAILED);

            $successes[$project->getId()] = $success;
            $failures[$project->getId()] = $failure;
        }

        $summaryView = new Framework\View('SummaryTable');
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
        $buildResult = $this->buildStore->find()->limit(5)->order('id', 'DESC')->get();
        $view        = new Framework\View('BuildsTable');

        $builds = new BuildCollection();

        foreach ($buildResult as $key => $build) {
            $builds->addBuild($key, BuildFactory::getBuild($build));
        }

        $view->builds   = $builds;

        return $view->render();
    }

    /**
     * Get a summary of the project groups we have, and what projects they have in them.
     * @return array
     */
    protected function getGroupInfo()
    {
        $rtn = [];
        $groups = $this->groupStore->find()->limit(100)->order('title', 'ASC')->get();

        foreach ($groups as $group) {
            $thisGroup = ['title' => $group->getTitle()];
            $projects = $this->projectStore->getByGroupId($group->getId());
            $thisGroup['projects'] = $projects;
            $thisGroup['summary'] = $this->getSummaryHtml($thisGroup['projects']);
            $rtn[] = $thisGroup;
        }

        return $rtn;
    }
}
