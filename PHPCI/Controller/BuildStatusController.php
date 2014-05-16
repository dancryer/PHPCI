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
use b8\Exception\HttpException\NotFoundException;
use b8\Store;
use PHPCI\BuildFactory;
use PHPCI\Model\Project;
use PHPCI\Model\Build;

/**
* Build Status Controller - Allows external access to build status information / images.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class BuildStatusController extends \PHPCI\Controller
{
    /**
     * @var \PHPCI\Store\ProjectStore
     */
    protected $projectStore;
    protected $buildStore;

    public function init()
    {
        $this->response->disableLayout();
        $this->buildStore      = Store\Factory::getStore('Build');
        $this->projectStore    = Store\Factory::getStore('Project');
    }

    /**
     * Returns status of the last build
     * @param $projectId
     * @return string
     */
    protected function getStatus($projectId)
    {
        $branch = $this->getParam('branch', 'master');
        try {
            $project = $this->projectStore->getById($projectId);
            $status = 'passing';

            if (!$project->getAllowPublicStatus()) {
                die();
            }

            if (isset($project) && $project instanceof Project) {
                $build = $project->getLatestBuild($branch, array(2,3));

                if (isset($build) && $build instanceof Build && $build->getStatus() != 2) {
                    $status = 'failed';
                }
            }
        } catch (\Exception $e) {
            $status = 'error';
        }

        return $status;
    }

    /**
    * Returns the appropriate build status image for a given project.
    */
    public function image($projectId)
    {
        $status = $this->getStatus($projectId);
        header('Content-Type: image/png');
        die(file_get_contents(APPLICATION_PATH . 'public/assets/img/build-' . $status . '.png'));
    }

    /**
    * Returns the appropriate build status image in SVG format for a given project.
    */
    public function svg($projectId)
    {
        $status = $this->getStatus($projectId);
        header('Content-Type: image/svg+xml');
        die(file_get_contents(APPLICATION_PATH . 'public/assets/img/build-' . $status . '.svg'));
    }

    public function view($projectId)
    {
        $project = $this->projectStore->getById($projectId);

        if (empty($project)) {
            throw new NotFoundException('Project with id: ' . $projectId . ' not found');
        }

        if (!$project->getAllowPublicStatus()) {
            throw new NotFoundException('Project with id: ' . $projectId . ' not found');
        }

        $builds = $this->getLatestBuilds($projectId);

        if (count($builds)) {
            $this->view->latest = $builds[0];
        }

        $this->view->builds = $builds;
        $this->view->project = $project;

        return $this->view->render();
    }

    /**
     * Render latest builds for project as HTML table.
     */
    protected function getLatestBuilds($projectId)
    {
        $criteria       = array('project_id' => $projectId);
        $order          = array('id' => 'DESC');
        $builds         = $this->buildStore->getWhere($criteria, 10, 0, array(), $order);

        foreach ($builds['items'] as &$build) {
            $build = BuildFactory::getBuild($build);
        }

        return $builds['items'];
    }
}
