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
* Github Controller - Processes webhook pings from Github.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class GithubController extends b8\Controller
{
    public function init()
    {
        $this->_buildStore      = Store\Factory::getStore('Build');
    }

    /**
    * Called by Github Webhooks:
    */
    public function webhook($project)
    {
        $payload    = json_decode($this->getParam('payload'), true);

        try {
            $build      = new Build();
            $build->setProjectId($project);
            $build->setCommitId($payload['after']);
            $build->setStatus(0);
            $build->setLog('');
            $build->setCreated(new \DateTime());
            $build->setBranch(str_replace('refs/heads/', '', $payload['ref']));
        } catch (\Exception $ex) {
            header('HTTP/1.1 400 Bad Request');
            header('Ex: ' . $ex->getMessage());
            die('FAIL');
        }

        try {
            $build = $this->_buildStore->save($build);
            $build->sendStatusPostback();
        } catch (\Exception $ex) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Ex: ' . $ex->getMessage());
            die('FAIL');
        }
        
        die('OK');
    }
}
