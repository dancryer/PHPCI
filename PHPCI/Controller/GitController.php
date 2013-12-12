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
 * @author Sami Tikka <stikka@iki.fi>
 */
class GitController extends \PHPCI\Controller
{
    public function init()
    {
        $this->_buildStore = Store\Factory::getStore('Build');
    }

    /**
     * Called by POSTing to /git/webhook/<project_id>?branch=<branch>&commit=<commit>
     *
     * @param string $project
     */
    public function webhook($project)
    {
        $branch = $this->getParam('branch');
        $commit = $this->getParam('commit');

        try {
            $build = new Build();
            $build->setProjectId($project);

            if ($branch !== null && trim($branch) !== '') {
                $build->setBranch($branch);
            } else {
                $build->setBranch('master');
            }

            if ($commit !== null && trim($commit) !== '') {
                $build->setCommitId($commit);
            }

            $build->setStatus(Build::STATUS_NEW);
            $build->setLog('');
            $build->setCreated(new \DateTime());
        } catch (\Exception $ex) {
            header('HTTP/1.1 400 Bad Request');
            header('Ex: ' . $ex->getMessage());
            die('FAIL');
        }

        try {
           $this->_buildStore->save($build);
        } catch (\Exception $ex) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Ex: ' . $ex->getMessage());
            die('FAIL');
        }

        die('OK');
    }
}
