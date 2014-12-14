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
    /* @var \PHPCI\Store\ProjectStore */
    protected $projectStore;
    /* @var \PHPCI\Store\BuildStore */
    protected $buildStore;

    /**
     * Initialise the controller, set up stores and services.
     */
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
     * Displays projects information in ccmenu format
     *
     * @param $projectId
     * @throws b8\Exception\HttpException
     * @author Vaidas Zilionis <vaidas@zilionis.net>
     */
    public function ccmenu($projectId) {
        /* @var Project $project */
        $project = $this->projectStore->getById($projectId);
        $xml = new \SimpleXMLElement('<projects/>');
        try {
            if (!$project instanceof Project) {
                throw new \Exception('Project not found', 404);
            }
            if (!$project->getAllowPublicStatus()) {
                echo $xml;
            };

            $branchList = $this->buildStore->getBuildBranches($projectId);
            if (!$branchList) {
                $branchList = array($project->getBranch());
            }

            $projectsXmlChild = $xml->addChild('Projects');
            foreach ($branchList as $branch) {
                $branchBuildStatus = $this->getBuildStatus($project, $branch, $project->getLatestBuild($branch));
                $projectXml = $projectsXmlChild->addChild('Project');
                $projectXml->addAttribute('name', $branchBuildStatus['name']);
                $projectXml->addAttribute('activity', $branchBuildStatus['activity']);
                $projectXml->addAttribute('lastBuildLabel', $branchBuildStatus['buildLabel']);
                $projectXml->addAttribute('lastBuildStatus', $branchBuildStatus['buildStatus']);
                $projectXml->addAttribute('lastBuildTime', $branchBuildStatus['lastBuildTime']);
                $projectXml->addAttribute('webUrl', $branchBuildStatus['webUrl']);
            }
        } catch(\Exception $e) {
            $xml = new \SimpleXMLElement('<projects/>');
        }
        Header('Content-type: text/xml');
        print($xml->asXML());
    }

    /**
     * @param Project $project
     * @param string $branch
     * @param Build $build
     * @return string
     * @author Vaidas Zilionis <vaidas@zilionis.net>
     */
    protected function getBuildStatus(Project $project, $branch, Build $build = null)
    {
        if ($build instanceof Build) {
            $buildStatusId = $build->getStatus();
        } else {
            return null;
        }

        $activityText = 'Sleeping';
        switch ($buildStatusId) {
            case 0:
            case 1:
                /** @var  Build $lastFinishedBuild */
                $lastFinishedBuild = $project->getLatestBuild($branch, array(2,3));
                $lastFinishedBuildInfo = $this->getBuildStatus($project, $branch, $lastFinishedBuild);
                $buildLabel = ($lastFinishedBuildInfo) ? $lastFinishedBuildInfo['buildLabel'] : '';
                $buildStatus = ($lastFinishedBuildInfo) ? $lastFinishedBuildInfo['buildStatus'] : '';
                $lastBuildTime = ($lastFinishedBuildInfo) ? $lastFinishedBuildInfo['lastBuildTime'] : '';
                $webUrl = ($lastFinishedBuildInfo) ? $lastFinishedBuildInfo['webUrl'] : '';
                if ($buildStatusId == 1) {
                    $activityText = 'Building';
                    $lastBuildTime = $build->getStarted()->format("Y-m-d\TH:i:sO");
                } else {
                    $activityText = 'Unknown';
                }
                break;
            case 2:
            case 3:
                $buildStatus = ($buildStatusId == 2) ? 'Success' : 'Failure';
                $finishedDateTime = $build->getFinished();
                $buildLabel = ($finishedDateTime) ? $build->getId() : '';
                $lastBuildTime = ($finishedDateTime) ?
                    $finishedDateTime->format("Y-m-d\TH:i:sO") : '';
                $webUrl = ($finishedDateTime) ? PHPCI_URL. 'build/view/' . $build->getId() : '';
                break;

        }

        return array(
            'name' => $build->getProjectTitle() . ' / ' . $branch,
            'activity' => $activityText,
            'buildLabel' => $buildLabel,
            'buildStatus' => $buildStatus,
            'lastBuildTime' => $lastBuildTime,
            'webUrl' => $webUrl,
        );
    }

    /**
    * Returns the appropriate build status image in SVG format for a given project.
    */
    public function image($projectId)
    {
        $status = $this->getStatus($projectId);
        $color = ($status == 'passing') ? 'green' : 'red';

        header('Content-Type: image/svg+xml');
        die(file_get_contents('http://img.shields.io/badge/build-' . $status . '-' . $color . '.svg'));
    }

    /**
     * View the public status page of a given project, if enabled.
     * @param $projectId
     * @return string
     * @throws \b8\Exception\HttpException\NotFoundException
     */
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
