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
use PHPCI\Model\Build;

/**
* BitBucket Controller - Processes webhook pings from BitBucket.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class BitbucketController extends \PHPCI\Controller
{
    /**
     * @var \PHPCI\Store\BuildStore
     */
    protected $buildStore;
    
    public function init()
    {
        $this->buildStore = Store\Factory::getStore('Build');
    }

    /**
    * Called by Bitbucket POST service.
    */
    public function webhook($project)
    {
        $payload = json_decode($this->getParam('payload'), true);
        $branches = array();
        $commits = array();

        foreach ($payload['commits'] as $commit) {
            if (!in_array($commit['branch'], $branches)) {
                $branches[]                 = $commit['branch'];
                $commits[$commit['branch']] = $commit['raw_node'];
            }
        }

        foreach ($branches as $branch) {
            try {

                $build = new Build();
                $build->setProjectId($project);
                $build->setCommitId($commits[$branch]);
                $build->setStatus(0);
                $build->setLog('');
                $build->setCreated(new \DateTime());
                $build->setBranch($branch);
                $this->buildStore->save($build);
            } catch (\Exception $ex) {
            }
        }
        
        die('OK');
    }
}
