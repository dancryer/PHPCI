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
     * Return information for a specific build in JSON format using project and
     * commit hash. This can be used to integrate a custom Gitlab CI server.
     *
     * @param int $projectId
     *   The project Id
     *
     * @param string $commitId
     *   The commit hash
     *
     * @return string
     *   JSON date to be visible on request. Response is an object with:
     *     - branch: branch name
     *     - commit: commit hash
     *     - message: commit message
     *     - committer: committer mail
     *     - id: build id
     *     - project: project name configured on PHPCI
     *     - status: build status in string format
     *     - log: the build log result
     *     - created: build creation time in ISO8601 format
     *     - started: build starting time in ISO8601 format
     *     - finished: build finishing time in ISO8601 format
     */
    public function status($projectId, $commitId)
    {
        // Find the builds.
        $builds = $this->buildStore->getByProjectAndCommit($projectId, $commitId);

        foreach ($builds['items'] as &$build) {
            $build = BuildFactory::getBuild($build);
        }

        // Extract the first and unique, if no build available return false.
        $build = reset($builds['items']);

        if (!$build) {
            $this->response->setResponseCode(404);
        } elseif (!$build->getProject()->getAllowPublicStatus()) {
            $this->response->setResponseCode(401);
        } elseif ($build) {
            $this->response->disableLayout();
            $this->response->setResponseCode(200);
            $this->response->setHeader('Content-Type', 'application/json');
            $this->response->setContent($build->convertJSON());
        }

        echo $this->response->flush();
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
