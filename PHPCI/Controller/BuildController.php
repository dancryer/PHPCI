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
use b8\Registry;
use PHPCI\Model\Build;

/**
* Build Controller - Allows users to run and view builds.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class BuildController extends b8\Controller
{
    public function init()
    {
        $this->_buildStore      = b8\Store\Factory::getStore('Build');
    }

    /**
    * View a specific build.
    */
    public function view($buildId)
    {
        $build          = $this->_buildStore->getById($buildId);
        $view           = new b8\View('Build');
        $view->build    = $build;
        $view->data     = $this->getBuildData($buildId);

        return $view->render();
    }

    /**
    * AJAX call to get build data:
    */
    public function data($buildId)
    {
        die($this->getBuildData($buildId));
    }

    /**
    * Get build data from database and json encode it:
    */
    protected function getBuildData($buildId)
    {
        $build  = $this->_buildStore->getById($buildId);

        $data               = array();
        $data['status']     = (int)$build->getStatus();
        $data['log']        = $this->cleanLog($build->getLog());
        $data['plugins']    = json_decode($build->getPlugins(), true);
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
        $copy   = $this->_buildStore->getById($buildId);

        $build  = new Build();
        $build->setProjectId($copy->getProjectId());
        $build->setCommitId($copy->getCommitId());
        $build->setStatus(0);
        $build->setBranch($copy->getBranch());
        $build->setCreated(new \DateTime());

        $build = $this->_buildStore->save($build);

        header('Location: /build/view/' . $build->getId());
    }

    /**
    * Delete a build.
    */
    public function delete($buildId)
    {
        if (!Registry::getInstance()->get('user')->getIsAdmin()) {
            throw new \Exception('You do not have permission to do that.');
        }
        
        $build  = $this->_buildStore->getById($buildId);
        $this->_buildStore->delete($build);

        header('Location: /project/view/' . $build->getProjectId());
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
}
