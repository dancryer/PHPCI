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

        foreach ($payload['commits'] as $commit) {
            try {
                $email = $commit['raw_author'];
                $email = substr($email, 0, strpos($email, '>'));
                $email = substr($email, strpos($email, '<') + 1);

                $build = new Build();
                $build->setProjectId($project);
                $build->setCommitId($commit['raw_node']);
                $build->setCommitterEmail($email);
                $build->setStatus(Build::STATUS_NEW);
                $build->setLog('');
                $build->setCreated(new \DateTime());
                $build->setBranch($commit['branch']);
                $build->setCommitMessage($commit['message']);
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
            $this->buildStore->save($build); /** bugfix: Errors with PHPCI GitHub hook #296 */
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
        $payload = json_decode($this->getParam('payload'), true);

        // Handle Pull Request web hooks:
        if (array_key_exists('pull_request', $payload)) {
            return $this->githubPullRequest($project, $payload);
        }

        // Handle Push web hooks:
        if (array_key_exists('commits', $payload)) {
            return $this->githubCommitRequest($project, $payload);
        }

        header('HTTP/1.1 200 OK');
        die('This request type is not supported, this is not an error.');
    }

    protected function githubCommitRequest($project, array $payload)
    {
        // Github sends a payload when you close a pull request with a
        // non-existant commit. We don't want this.
        if (array_key_exists('after', $payload) && $payload['after'] === '0000000000000000000000000000000000000000') {
            die('OK');
        }

        try {

            if (isset($payload['commits']) && is_array($payload['commits'])) {
                // If we have a list of commits, then add them all as builds to be tested:

                foreach ($payload['commits'] as $commit) {
                    if (!$commit['distinct']) {
                        continue;
                    }

                    $build = new Build();
                    $build->setProjectId($project);
                    $build->setCommitId($commit['id']);
                    $build->setStatus(Build::STATUS_NEW);
                    $build->setLog('');
                    $build->setCreated(new \DateTime());
                    $build->setBranch(str_replace('refs/heads/', '', $payload['ref']));
                    $build->setCommitterEmail($commit['committer']['email']);
                    $build->setCommitMessage($commit['message']);
                    $build = $this->buildStore->save($build);
                    $build->sendStatusPostback();
                }
            } elseif (substr($payload['ref'], 0, 10) == 'refs/tags/') {
                // If we don't, but we're dealing with a tag, add that instead:
                $build = new Build();
                $build->setProjectId($project);
                $build->setCommitId($payload['after']);
                $build->setStatus(Build::STATUS_NEW);
                $build->setLog('');
                $build->setCreated(new \DateTime());
                $build->setBranch(str_replace('refs/tags/', 'Tag: ', $payload['ref']));
                $build->setCommitterEmail($payload['pusher']['email']);
                $build->setCommitMessage($payload['head_commit']['message']);

                $build = $this->buildStore->save($build);
                $build->sendStatusPostback();
            }

        } catch (\Exception $ex) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Ex: ' . $ex->getMessage());
            die('FAIL');
        }

        die('OK');
    }

    protected function githubPullRequest($projectId, array $payload)
    {
        // We only want to know about open pull requests:
        if (!in_array($payload['action'], array('opened', 'synchronize', 'reopened'))) {
            die('OK');
        }

        try {
            $headers = array();
            $token = \b8\Config::getInstance()->get('phpci.github.token');

            if (!empty($token)) {
                $headers[] = 'Authorization: token ' . $token;
            }

            $url    = $payload['pull_request']['commits_url'];
            $http   = new \b8\HttpClient();
            $http->setHeaders($headers);
            $response = $http->get($url);

            // Check we got a success response:
            if (!$response['success']) {
                header('HTTP/1.1 500 Internal Server Error');
                header('Ex: Could not get commits, failed API request.');
                die('FAIL');
            }

            foreach ($response['body'] as $commit) {
                $build = new Build();
                $build->setProjectId($projectId);
                $build->setCommitId($commit['sha']);
                $build->setStatus(Build::STATUS_NEW);
                $build->setLog('');
                $build->setCreated(new \DateTime());
                $build->setBranch(str_replace('refs/heads/', '', $payload['pull_request']['base']['ref']));
                $build->setCommitterEmail($commit['commit']['author']['email']);
                $build->setCommitMessage($commit['commit']['message']);

                $extra = array(
                    'build_type' => 'pull_request',
                    'pull_request_id' => $payload['pull_request']['id'],
                    'pull_request_number' => $payload['number'],
                    'remote_branch' => $payload['pull_request']['head']['ref'],
                    'remote_url' => $payload['pull_request']['head']['repo']['clone_url'],
                );

                $build->setExtra(json_encode($extra));

                $build = $this->buildStore->save($build);
                $build->sendStatusPostback();
            }
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
        $payloadString = file_get_contents("php://input");
        $payload = json_decode($payloadString, true);

        try {

            if (isset($payload['commits']) && is_array($payload['commits'])) {
                // If we have a list of commits, then add them all as builds to be tested:

                foreach ($payload['commits'] as $commit) {
                    $build = new Build();
                    $build->setProjectId($project);
                    $build->setCommitId($commit['id']);
                    $build->setStatus(Build::STATUS_NEW);
                    $build->setLog('');
                    $build->setCreated(new \DateTime());
                    $build->setBranch(str_replace('refs/heads/', '', $payload['ref']));
                    $build->setCommitterEmail($commit['author']['email']);
                    $build->setCommitMessage($commit['message']);
                    $build = $this->buildStore->save($build);
                    $build->sendStatusPostback();
                }
            }

        } catch (\Exception $ex) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Ex: ' . $ex->getMessage());
            die('FAIL');
        }

        die('OK');
    }
}
