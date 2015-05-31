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
use b8\Exception\HttpException\NotAuthorizedException;
use b8\Store;
use b8\HttpClient;
use b8\Http\Request;
use b8\Http\Response;
use Exception;
use PHPCI\Config;
use PHPCI\BuildFactory;
use PHPCI\Helper\Lang;
use PHPCI\Model\Project;
use PHPCI\Model\Build;
use PHPCI\Service\BuildStatusService;
use PHPCI\Store\BuildStore;
use PHPCI\Store\ProjectStore;

/**
* Build Status Controller - Allows external access to build status information / images.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class BuildStatusController extends \PHPCI\Controller
{
    /**
     * @var BuildStore
     */
    protected $buildStore;

    /**
     * @var ProjectStore
     */
    protected $projectStore;

    /**
     * Create the BuildStatus controller.
     *
     * @param Config       $config
     * @param Request      $request
     * @param Response     $response
     * @param BuildStore   $buildStore
     * @param ProjectStore $projectStore
     * @param HttpClient   $shieldsClient
     */
    public function __construct(
        Config $config,
        Request $request,
        Response $response,
        BuildStore $buildStore,
        ProjectStore $projectStore,
        HttpClient $shieldsClient
    ) {
        parent::__construct($config, $request, $response);

        $this->buildStore = $buildStore;
        $this->projectStore = $projectStore;
        $this->shieldsClient = $shieldsClient;
    }

    /**
     * Displays projects information in ccmenu format
     *
     * @param int $projectId
     *
     * @return Response
     *
     * @throws Exception
     * @throws HttpException
     */
    public function ccxml($projectId)
    {
        /* @var Project $project */
        $project = $this->projectStore->getById($projectId);

        if (!$project instanceof Project) {
            throw new NotFoundException(Lang::get('project_x_not_found', $projectId));
        }

        if (!$project->getAllowPublicStatus()) {
            throw new NotAuthorizedException();
        }

        $xml = new \SimpleXMLElement('<Projects/>');
        try {
            $branchList = $this->buildStore->getBuildBranches($projectId);

            if (!$branchList) {
                $branchList = array($project->getBranch());
            }

            foreach ($branchList as $branch) {
                $buildStatusService = new BuildStatusService($branch, $project, $project->getLatestBuild($branch));
                if ($attributes = $buildStatusService->toArray()) {
                    $projectXml = $xml->addChild('Project');
                    foreach ($attributes as $attributeKey => $attributeValue) {
                        $projectXml->addAttribute($attributeKey, $attributeValue);
                    }
                }
            }
        } catch (\Exception $e) {
            $xml = new \SimpleXMLElement('<projects/>');
        }

        $this->response->disableLayout();
        $this->response->setHeader('Content-Type', 'text/xml');
        $this->response->setContent($xml->asXML());

        return $this->response;
    }

    /**
    * Returns the appropriate build status image in SVG format for a given project.
    *
    * @param int $projectId
    *
    * @return Response
    */
    public function image($projectId)
    {
        $project = $this->projectStore->getById($projectId);

        if (empty($project)) {
            throw new NotFoundException(Lang::get('project_x_not_found', $projectId));
        }

        if (!$project->getAllowPublicStatus()) {
            throw new NotAuthorizedException();
        }

        $style = $this->getParam('style', 'plastic');
        $label = $this->getParam('label', 'build');
        $branch = $this->getParam('branch', 'master');
        $status = $this->getStatus($project, $branch);

        $color = ($status == 'passing') ? 'green' : 'red';
        $image = $this->shieldsClient->get(
            sprintf('/badge/%s-%s-%s.svg', $label, $status, $color),
            array('style' => $style)
        );

        $this->response->disableLayout();
        $this->response->setHeader('Content-Type', 'image/svg+xml');
        $this->response->setContent($image['body']);

        return $this->response;
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
            throw new NotFoundException(Lang::get('project_x_not_found', $projectId));
        }

        if (!$project->getAllowPublicStatus()) {
            throw new NotAuthorizedException();
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

    /**
     * Returns status of the last build
     *
     * @param int $projectId
     *
     * @return string
     *
     * @throws Exception
     */
    protected function getStatus($project, $branch)
    {
        try {
            $build = $project->getLatestBuild($branch, array(2,3));

            if (isset($build) && $build instanceof Build && $build->getStatus() != 2) {
                return 'failed';
            }
        } catch (Exception $e) {
            return 'error';
        }

        return 'passing';
    }
}
