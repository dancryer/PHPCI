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
use b8\Http\Response\JsonResponse;
use b8\Http\Request;
use b8\Http\Response;
use PHPCI\Config;
use PHPCI\BuildFactory;
use PHPCI\Helper\AnsiConverter;
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;
use PHPCI\Model\Project;
use PHPCI\Store\BuildStore;
use PHPCI\Service\BuildService;

/**
* Build Controller - Allows users to run and view builds.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class BuildController extends \PHPCI\Controller
{
    /**
     * @var BuildStore
     */
    protected $buildStore;

    /**
     * @var BuildService
     */
    protected $buildService;

    /**
     * @var BuildFactory
     */
    protected $buildFactory;

    /**
     * Create the Build controller
     *
     * @param Config       $config
     * @param Request      $request
     * @param Response     $response
     * @param BuildStore   $buildStore
     * @param BuildService $buildService
     */
    public function __construct(
        Config $config,
        Request $request,
        Response $response,
        BuildStore $buildStore,
        BuildService $buildService,
        BuildFactory $buildFactory
    ) {
        parent::__construct($config, $request, $response);

        $this->buildStore = $buildStore;
        $this->buildService = $buildService;
        $this->buildFactory = $buildFactory;
    }

    /**
    * View a specific build.
    */
    public function view($buildId)
    {
        try {
            $build = $this->buildFactory->getBuildById($buildId);
        } catch (\Exception $ex) {
            $build = null;
        }

        if (empty($build)) {
            throw new NotFoundException(Lang::get('build_x_not_found', $buildId));
        }

        $this->view->plugins  = $this->getUiPlugins();
        $this->view->build    = $build;
        $this->view->data     = json_encode($this->getBuildData($build));

        $this->layout->title = Lang::get('build_n', $buildId);
        $this->layout->subtitle = $build->getProjectTitle();

        $nav = array(
            'title' => Lang::get('build_n', $buildId),
            'icon' => 'cog',
            'links' => array(
                'build/rebuild/' . $build->getId() => Lang::get('rebuild_now'),
            ),
        );

        if ($this->currentUserIsAdmin()) {
            $nav['links']['build/delete/' . $build->getId()] = Lang::get('delete_build');
        }

        $this->layout->nav = $nav;
    }

    /**
     * Returns an array of the JS plugins to include.
     * @return array
     */
    protected function getUiPlugins()
    {
        $rtn = array();
        $path = APPLICATION_PATH . 'public/assets/js/build-plugins/';
        $dir = opendir($path);

        while ($item = readdir($dir)) {
            if (substr($item, 0, 1) == '.' || substr($item, -3) != '.js') {
                continue;
            }

            $rtn[] = $item;
        }

        return $rtn;
    }

    /**
    * AJAX call to get build data:
    */
    public function data($buildId)
    {
        $response = new JsonResponse();
        $build = $this->buildFactory->getBuildById($buildId);

        if (!$build) {
            $response->setResponseCode(404);
            $response->setContent(array());
            return $response;
        }

        $response->setContent($this->getBuildData($build));
        return $response;
    }

    /**
     * AJAX call to get build meta:
     */
    public function meta($buildId)
    {
        $build  = $this->buildFactory->getBuildById($buildId);
        $key = $this->getParam('key', null);
        $numBuilds = $this->getParam('num_builds', 1);
        $data = null;

        if ($key && $build) {
            $data = $this->buildStore->getMeta($key, $build->getProjectId(), $buildId, $build->getBranch(), $numBuilds);
        }

        $response = new JsonResponse();
        $response->setContent($data);
        return $response;
    }

    /**
    * Get build data from database and json encode it:
    */
    protected function getBuildData($build)
    {
        $data               = array();
        $data['status']     = (int)$build->getStatus();
        $data['log']        = $this->cleanLog($build->getLog());
        $data['created']    = !is_null($build->getCreated()) ? $build->getCreated()->format('Y-m-d H:i:s') : null;
        $data['started']    = !is_null($build->getStarted()) ? $build->getStarted()->format('Y-m-d H:i:s') : null;
        $data['finished']   = !is_null($build->getFinished()) ? $build->getFinished()->format('Y-m-d H:i:s') : null;

        return $data;
    }

    /**
    * Create a build using an existing build as a template:
    */
    public function rebuild($buildId)
    {
        $copy = $this->buildFactory->getBuildById($buildId);

        if (empty($copy)) {
            throw new NotFoundException(Lang::get('build_x_not_found', $buildId));
        }

        $build = $this->buildService->createDuplicateBuild($copy);

        $response = new b8\Http\Response\RedirectResponse();
        $response->setHeader('Location', PHPCI_URL.'build/view/' . $build->getId());
        return $response;
    }

    /**
    * Delete a build.
    */
    public function delete($buildId)
    {
        $this->requireAdmin();

        $build = $this->buildFactory->getBuildById($buildId);

        if (empty($build)) {
            throw new NotFoundException(Lang::get('build_x_not_found', $buildId));
        }

        $this->buildService->deleteBuild($build);

        $response = new b8\Http\Response\RedirectResponse();
        $response->setHeader('Location', PHPCI_URL.'project/view/' . $build->getProjectId());
        return $response;
    }

    /**
    * Parse log for unix colours and replace with HTML.
    */
    protected function cleanLog($log)
    {
        return AnsiConverter::convert($log);
    }

    /**
     * Allows the UI to poll for the latest running and pending builds.
     */
    public function latest()
    {
        $rtn = array(
            'pending' => $this->formatBuilds($this->buildStore->getByStatus(Build::STATUS_NEW)),
            'running' => $this->formatBuilds($this->buildStore->getByStatus(Build::STATUS_RUNNING)),
        );

        $response = new JsonResponse();
        $response->setContent($rtn);

        return $response;
    }

    /**
     * Formats a list of builds into rows suitable for the dropdowns in the PHPCI header bar.
     * @param $builds
     * @return array
     */
    protected function formatBuilds($builds)
    {
        Project::$sleepable = array('id', 'title', 'reference', 'type');

        $rtn = array('count' => $builds['count'], 'items' => array());

        foreach ($builds['items'] as $build) {
            $item = $build->toArray(1);

            $header = new b8\View('Build/header-row');
            $header->build = $build;

            $item['header_row'] = $header->render();
            $rtn['items'][$item['id']] = $item;
        }

        ksort($rtn['items']);
        return $rtn;
    }
}
