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
use PHPCI\BuildFactory;
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;
use PHPCI\Model\Project;
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
     * @var \PHPCI\Store\BuildStore
     */
    protected $buildStore;

    /**
     * @var \PHPCI\Service\BuildService
     */
    protected $buildService;
    
    public function init()
    {
        $this->buildStore = b8\Store\Factory::getStore('Build');
        $this->buildService = new BuildService($this->buildStore);
    }

    /**
    * View a specific build.
    */
    public function view($buildId)
    {
        try {
            $build = BuildFactory::getBuildById($buildId);
        } catch (\Exception $ex) {
            $build = null;
        }

        if (empty($build)) {
            throw new NotFoundException(Lang::get('build_x_not_found', $buildId));
        }

        $this->view->plugins  = $this->getUiPlugins();
        $this->view->build    = $build;
        $this->view->data     = $this->getBuildData($build);

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
        die($this->getBuildData(BuildFactory::getBuildById($buildId)));
    }

    /**
     * AJAX call to get build meta:
     */
    public function meta($buildId)
    {
        $build  = BuildFactory::getBuildById($buildId);
        $key = $this->getParam('key', null);
        $numBuilds = $this->getParam('num_builds', 1);
        $data = null;

        if ($key && $build) {
            $data = $this->buildStore->getMeta($key, $build->getProjectId(), $buildId, $numBuilds);
        }

        die(json_encode($data));
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

        return json_encode($data);
    }

    /**
    * Create a build using an existing build as a template:
    */
    public function rebuild($buildId)
    {
        $copy   = BuildFactory::getBuildById($buildId);

        if (empty($copy)) {
            throw new NotFoundException(Lang::get('build_x_not_found', $buildId));
        }

        $build = $this->buildService->createDuplicateBuild($copy);

        header('Location: '.PHPCI_URL.'build/view/' . $build->getId());
        exit;
    }

    /**
    * Delete a build.
    */
    public function delete($buildId)
    {
        $this->requireAdmin();

        $build = BuildFactory::getBuildById($buildId);

        if (empty($build)) {
            throw new NotFoundException(Lang::get('build_x_not_found', $buildId));
        }

        $this->buildService->deleteBuild($build);

        header('Location: '.PHPCI_URL.'project/view/' . $build->getProjectId());
        exit;
    }

    /**
    * Parse log for unix colours and replace with HTML.
    */
    protected function cleanLog($log)
    {
        $log = str_replace('[0;32m', '<span style="color: green">', $log);
        $log = str_replace('[0;31m', '<span style="color: red">', $log);
        $log = str_replace('[0m', '</span>', $log);

        return $log;
    }

    public function latest()
    {
        $rtn = array(
            'pending' => $this->formatBuilds($this->buildStore->getByStatus(Build::STATUS_NEW)),
            'running' => $this->formatBuilds($this->buildStore->getByStatus(Build::STATUS_RUNNING)),
        );

        if ($this->request->isAjax()) {
            die(json_encode($rtn));
        }
    }

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
