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
* Remote Controller - Allows remote external access and manage.
* @author       Prikhodko Sergey <indigo.dp@gmail.com>
* @package      PHPCI
* @subpackage   Web
*/
class RemoteController extends \PHPCI\Controller
{
    /**
     * @var \PHPCI\Store\ProjectStore
     */
    protected $projectStore;

    public function init()
    {
        $this->projectStore = Store\Factory::getStore('Project');
    }
  
    /**
     * Get latest build id
     * @param int $projectId
     */
    public function latestBuild($projectId)
    {
        $branch = $this->getParam('branch', 'master');
        $project = $this->projectStore->getById($projectId);

        if (isset($project) && $project instanceof Project) {
            $build = $project->getLatestBuild($branch);

            if (isset($build) && $build instanceof Build) {
                die($build->getId());
            }
        }
        
        die();
    }
    
}
