<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Controller;

use b8;
use b8\Http\Request;
use b8\Http\Response;
use PHPCI\Config;
use PHPCI\BuildFactory;
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;
use PHPCI\Store\BuildStore;
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
     * @var BuildStore
     */
    protected $buildStore;

    /**
     * @var ProjectStore
     */
    protected $projectStore;

    public function __construct(
        Config $config,
        Request $request,
        Response $response,
        BuildStore $buildStore,
        ProjectStore $projectStore
    )
    {
        parent::__construct($config, $request, $response);

        $this->buildStore = $buildStore;
        $this->projectStore = $projectStore;
    }

    /**
    * Display PHPCI dashboard:
    */
    public function index()
    {
        $this->layout->title = Lang::get('dashboard');

        $projects = $this->projectStore->getWhere(
            array('archived' => (int)isset($_GET['archived'])),
            50,
            0,
            array(),
            array('title' => 'ASC')
        );

        $builds = $this->buildStore->getLatestBuilds(null, 10);

        foreach ($builds as &$build) {
            $build = BuildFactory::getBuild($build);
        }

        $this->view->builds   = $builds;
        $this->view->projects = $projects['items'];
        $this->view->summary  = $this->getSummaryHtml($projects);

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
        $projects = $this->projectStore->getWhere(array(), 50, 0, array(), array('title' => 'ASC'));
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
        $summaryBuilds = array();
        $successes     = array();
        $failures      = array();
        $counts        = array();

        foreach ($projects['items'] as $project) {
            $summaryBuilds[$project->getId()] = $this->buildStore->getLatestBuilds($project->getId());

            $count = $this->buildStore->getWhere(
                array('project_id' => $project->getId()),
                1,
                0,
                array(),
                array('id' => 'DESC')
            );
            $counts[$project->getId()] = $count['count'];

            $success = $this->buildStore->getLastBuildByStatus($project->getId(), Build::STATUS_SUCCESS);
            $failure = $this->buildStore->getLastBuildByStatus($project->getId(), Build::STATUS_FAILED);

            $successes[$project->getId()] = $success;
            $failures[$project->getId()] = $failure;
        }

        $summaryView = new b8\View('SummaryTable');
        $summaryView->projects   = $projects['items'];
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
        $builds         = $this->buildStore->getWhere(array(), 5, 0, array(), array('id' => 'DESC'));
        $view           = new b8\View('BuildsTable');

        foreach ($builds['items'] as &$build) {
            $build = BuildFactory::getBuild($build);
        }

        $view->builds   = $builds['items'];

        return $view->render();
    }
}
