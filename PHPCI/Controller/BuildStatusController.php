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
    /**
     * @var \PHPCI\Store\ProjectStore
     */
    protected $projectStore;
    
    /**
     * @var \PHPCI\Store\BuildtStore
     */
    protected $buildStore;

    public function init()
    {
        $this->projectStore = Store\Factory::getStore('Project');
        $this->buildStore = Store\Factory::getStore('Build');
    }

    /**
    * Returns the appropriate build status image for a given project.
    */
    public function image($projectId)
    {
        $branch = $this->getParam('branch', 'master');
        $project = $this->projectStore->getById($projectId);
        $status = 'ok';

        if (isset($project) && $project instanceof Project) {
            $build = $project->getLatestBuild($branch, array(2,3));

            if (isset($build) && $build instanceof Build && $build->getStatus() != 2) {
                $status = 'failed';
            }
        }

        header('Content-Type: image/png');
        die(file_get_contents(APPLICATION_PATH . 'public/assets/img/build-' . $status . '.png'));
    }
    
    /**
     * Get build status
     * @param int $buildId
     */
    public function buildInfo($buildId)
    {
        $build = $this->buildStore->getById($buildId);
        
        if (isset($build) && $build instanceof Build) {            
            die($build->getStatus());    
        }
        
        die();
    }
}
