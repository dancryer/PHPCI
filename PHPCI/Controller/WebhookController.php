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
 * Webhook Controller - Processes webhook pings from BitBucket, Github, Gitlab, etc.
 * @author       Dan Cryer <dan@block8.co.uk>
 * @author       Sami Tikka <stikka@iki.fi>
 * @author       Alex Russell <alex@clevercherry.com>
 * @package      PHPCI
 * @subpackage   Web
 */
class WebhookController extends \PHPCI\Controller
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
    public function bitbucket($project)
    {
        $payload = json_decode($this->getParam('payload'), true);
        $branches = array();

        foreach ($payload['commits'] as $commit) {
            if (!in_array($commit['branch'], array_keys($commits))) {
                $commits[$commit['branch']] = $commit;
            }
        }

        foreach ($commits as $commit) {
            try {

                $build = new Build();
                $build->setProjectId($project);
                $build->setCommitId($commit['raw_node']);
                $build->setCommitterEmail($commit['raw_author']);
                $build->setStatus(Build::STATUS_NEW);
                $build->setLog('');
                $build->setCreated(new \DateTime());
                $build->setBranch($branch);
                $this->buildStore->save($build);
            } catch (\Exception $ex) {
            }
        }

        die('OK');
    }

    /**
     * Called by POSTing to /git/webhook/<project_id>?branch=<branch>&commit=<commit>
     *
     * @param string $project
     */
    public function git($project)
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

    /**
     * Called by Github Webhooks:
     */
    public function github($project)
    {
        $payload    = json_decode($this->getParam('payload'), true);

        // Github sends a payload when you close a pull request with a
        // non-existant commit. We don't want this.
        if ($payload['after'] === '0000000000000000000000000000000000000000') {
            die('OK');
        }

        try {
            $build      = new Build();
            $build->setProjectId($project);
            $build->setCommitId($payload['after']);
            $build->setStatus(Build::STATUS_NEW);
            $build->setLog('');
            $build->setCreated(new \DateTime());
            $build->setBranch(str_replace('refs/heads/', '', $payload['ref']));

            if (!empty($payload['pusher']['email'])) {
                $build->setCommitterEmail($payload['pusher']['email']);
            }

        } catch (\Exception $ex) {
            header('HTTP/1.1 400 Bad Request');
            header('Ex: ' . $ex->getMessage());
            die('FAIL');
        }

        try {
            $build = $this->buildStore->save($build);
            $build->sendStatusPostback();
        } catch (\Exception $ex) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Ex: ' . $ex->getMessage());
            die('FAIL');
        }

        die('OK');
    }

    /**
     * Called by Gitlab Webhooks:
     */
    public function gitlab($project)
    {
        $payload = json_decode(file_get_contents("php://input"), true);

        try {
            $build = new Build();
            $build->setProjectId($project);
            $build->setCommitId($payload['after']);
            $build->setStatus(Build::STATUS_NEW);
            $build->setLog('');
            $build->setCreated(new \DateTime());
            $build->setBranch(str_replace('refs/heads/', '', $payload['ref']));
        } catch (\Exception $ex) {
            header('HTTP/1.1 400 Bad Request');
            header('Ex: ' . $ex->getMessage());
            die('FAIL');
        }

        try {
            $build = $this->buildStore->save($build);
            $build->sendStatusPostback();
        } catch (\Exception $ex) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Ex: ' . $ex->getMessage());
            die('FAIL');
        }

        die('OK');
    }
}
