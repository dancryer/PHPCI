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
use b8\Store;
use PHPCI\BuildFactory;
use PHPCI\Service\BuildService;

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

    /**
     * @var \PHPCI\Store\ProjectStore
     */
    protected $projectStore;

    /**
     * @var \PHPCI\Service\BuildService
     */
    protected $buildService;

    /**
     * Initialise the controller, set up stores and services.
     */
    public function init()
    {
        $this->buildStore = Store\Factory::getStore('Build');
        $this->projectStore = Store\Factory::getStore('Project');
        $this->buildService = new BuildService($this->buildStore);
    }

    /**
     * Called by Bitbucket POST service.
     */
    public function bitbucket($project)
    {
        $response = new b8\Http\Response\JsonResponse();
        $response->setContent(array('status' => 'ok'));

        $payload = json_decode($this->getParam('payload'), true);

        foreach ($payload['commits'] as $commit) {
            try {
                $email = $commit['raw_author'];
                $email = substr($email, 0, strpos($email, '>'));
                $email = substr($email, strpos($email, '<') + 1);

                $this->createBuild($project, $commit['raw_node'], $commit['branch'], $email, $commit['message']);
            } catch (\Exception $ex) {
                $response->setResponseCode(500);
                $response->setContent(array('status' => 'failed', 'error' => $ex->getMessage()));
                break;
            }
        }

        return $response;
    }

    /**
     * Called by POSTing to /webhook/git/<project_id>?branch=<branch>&commit=<commit>
     *
     * @param string $project
     */
    public function git($project)
    {
        $response = new b8\Http\Response\JsonResponse();
        $response->setContent(array('status' => 'ok'));

        $branch = $this->getParam('branch');
        $commit = $this->getParam('commit');
        $commitMessage = $this->getParam('message');
        $committer = $this->getParam('committer');

        try {
            if (empty($branch)) {
                $branch = 'master';
            }

            if (empty($commit)) {
                $commit = null;
            }

            if (empty($commitMessage)) {
                $commitMessage = null;
            }

            if (empty($committer)) {
                $committer = null;
            }

            $this->createBuild($project, $commit, $branch, $committer, $commitMessage);
        } catch (\Exception $ex) {
            $response->setResponseCode(500);
            $response->setContent(array('status' => 'failed', 'error' => $ex->getMessage()));
        }

        return $response;
    }

    /**
     * Called by Github Webhooks:
     */
    public function github($project)
    {
        $response = new b8\Http\Response\JsonResponse();
        $response->setContent(array('status' => 'ok'));

        switch ($_SERVER['CONTENT_TYPE']) {
            case 'application/json':
                $payload = json_decode(file_get_contents('php://input'), true);
                break;

            case 'application/x-www-form-urlencoded':
                $payload = json_decode($this->getParam('payload'), true);
                break;

            default:
                $response->setResponseCode(401);
                $response->setContent(array('status' => 'failed', 'error' => 'Content type not supported.'));
                return $response;
        }

        // Handle Pull Request web hooks:
        if (array_key_exists('pull_request', $payload)) {
            return $this->githubPullRequest($project, $payload, $response);
        }

        // Handle Push web hooks:
        if (array_key_exists('commits', $payload)) {
            return $this->githubCommitRequest($project, $payload, $response);
        }

        return $response;
    }

    /**
     * Handle the payload when Github sends a commit webhook.
     * @param $project
     * @param array $payload
     */
    protected function githubCommitRequest($project, array $payload, b8\Http\Response\JsonResponse $response)
    {
        // Github sends a payload when you close a pull request with a
        // non-existant commit. We don't want this.
        if (array_key_exists('after', $payload) && $payload['after'] === '0000000000000000000000000000000000000000') {
            return $response;
        }

        try {

            if (isset($payload['commits']) && is_array($payload['commits'])) {
                // If we have a list of commits, then add them all as builds to be tested:

                foreach ($payload['commits'] as $commit) {
                    if (!$commit['distinct']) {
                        continue;
                    }

                    $branch = str_replace('refs/heads/', '', $payload['ref']);
                    $committer = $commit['committer']['email'];
                    $this->createBuild($project, $commit['id'], $branch, $committer, $commit['message']);
                }
            } elseif (substr($payload['ref'], 0, 10) == 'refs/tags/') {
                // If we don't, but we're dealing with a tag, add that instead:
                $branch = str_replace('refs/tags/', 'Tag: ', $payload['ref']);
                $committer = $payload['pusher']['email'];
                $message = $payload['head_commit']['message'];
                $this->createBuild($project, $payload['after'], $branch, $committer, $message);
            }

        } catch (\Exception $ex) {
            $response->setResponseCode(500);
            $response->setContent(array('status' => 'failed', 'error' => $ex->getMessage()));

        }

        return $response;
    }

    /**
     * Handle the payload when Github sends a Pull Request webhook.
     * @param $projectId
     * @param array $payload
     */
    protected function githubPullRequest($projectId, array $payload, b8\Http\Response\JsonResponse $response)
    {
        // We only want to know about open pull requests:
        if (!in_array($payload['action'], array('opened', 'synchronize', 'reopened'))) {
            return $response;
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
                $message = 'Could not get commits, failed API request.';
                $response->setResponseCode(500);
                $response->setContent(array('status' => 'failed', 'error' => $message));
                return $response;
            }

            foreach ($response['body'] as $commit) {
                $branch = str_replace('refs/heads/', '', $payload['pull_request']['base']['ref']);
                $committer = $commit['commit']['author']['email'];
                $message = $commit['commit']['message'];

                $extra = array(
                    'build_type' => 'pull_request',
                    'pull_request_id' => $payload['pull_request']['id'],
                    'pull_request_number' => $payload['number'],
                    'remote_branch' => $payload['pull_request']['head']['ref'],
                    'remote_url' => $payload['pull_request']['head']['repo']['clone_url'],
                );

                $this->createBuild($projectId, $commit['sha'], $branch, $committer, $message, $extra);
            }
        } catch (\Exception $ex) {
            $response->setResponseCode(500);
            $response->setContent(array('status' => 'failed', 'error' => $ex->getMessage()));
        }

        return $response;
    }

    /**
     * Called by Gitlab Webhooks:
     */
    public function gitlab($project)
    {
        $response = new b8\Http\Response\JsonResponse();
        $response->setContent(array('status' => 'ok'));

        $payloadString = file_get_contents("php://input");
        $payload = json_decode($payloadString, true);

        try {


            // build on merge request events
            if (isset($payload['object_kind']) && $payload['object_kind'] == 'merge_request') {
                $attributes = $payload['object_attributes'];
                if ($attributes['state'] == 'opened' || $attributes['state'] == 'reopened') {

                    $branch = $attributes['source_branch'];
                    $commit = $attributes['last_commit'];
                    $committer = $commit['author']['email'];

                    $this->createBuild($project, $commit['id'], $branch, $committer, $commit['message']);
                }
            }

            // build on push events
            if (isset($payload['commits']) && is_array($payload['commits'])) {
                // If we have a list of commits, then add them all as builds to be tested:

                foreach ($payload['commits'] as $commit) {
                    $branch = str_replace('refs/heads/', '', $payload['ref']);
                    $committer = $commit['author']['email'];
                    $this->createBuild($project, $commit['id'], $branch, $committer, $commit['message']);
                }
            }

        } catch (\Exception $ex) {
            $response->setResponseCode(500);
            $response->setContent(array('status' => 'failed', 'error' => $ex->getMessage()));
        }

        return $response;
    }

    /**
     * Wrapper for creating a new build.
     * @param $projectId
     * @param $commitId
     * @param $branch
     * @param $committer
     * @param $commitMessage
     * @param null $extra
     * @return bool
     * @throws \Exception
     */
    protected function createBuild($projectId, $commitId, $branch, $committer, $commitMessage, $extra = null)
    {
        // Check if a build already exists for this commit ID:
        $builds = $this->buildStore->getByProjectAndCommit($projectId, $commitId);

        if ($builds['count']) {
            return true;
        }

        $project = $this->projectStore->getById($projectId);

        if (empty($project)) {
            throw new \Exception('Project does not exist:' . $projectId);
        }

        // If not, create a new build job for it:
        $build = $this->buildService->createBuild($project, $commitId, $branch, $committer, $commitMessage, $extra);
        $build = BuildFactory::getBuild($build);

        // Send a status postback if the build type provides one:
        $build->sendStatusPostback();

        return true;
    }
}
