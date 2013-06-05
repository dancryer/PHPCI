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
use b8\Store;
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
    public function init()
    {
        $this->_projectStore      = Store\Factory::getStore('Project');
    }

    /**
    * Returns the appropriate build status image for a given project.
    */
    public function image($projectId)
    {
        $branch         = $this->getParam('branch', 'master');
        $project        = $this->_projectStore->getById($projectId);
        $status         = 'ok';

        if (isset($project) && $project instanceof Project) {
            $build = $project->getLatestBuild($branch, array(2,3));

            if (isset($build) && $build instanceof Build && $build->getStatus() != 2) {
                $status = 'failed';
            }
        }

        header('Content-Type: image/png');
        die(file_get_contents(APPLICATION_PATH . 'assets/img/build-' . $status . '.png'));
    }
}
