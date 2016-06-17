<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Model\Build;

use Bitbucket\API\Http\Client;
use Bitbucket\API\Http\Listener\NormalizeArrayListener;
use Bitbucket\API\Http\Listener\OAuthListener;
use PHPCI\Model\Build;

/**
* BitBucket Build Model
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Core
*/
class BitbucketBuild extends RemoteGitBuild
{
    /**
    * Get link to commit from another source (i.e. Github)
    */
    public function getCommitLink()
    {
        return 'https://bitbucket.org/' . $this->getProject()->getReference() . '/commits/' . $this->getCommitId();
    }

    /**
    * Get link to branch from another source (i.e. Github)
    */
    public function getBranchLink()
    {
        return 'https://bitbucket.org/' . $this->getProject()->getReference() . '/src/?at=' . $this->getBranch();
    }

    /**
    * Get the URL to be used to clone this remote repository.
    */
    protected function getCloneUrl()
    {
        $key = trim($this->getProject()->getSshPrivateKey());

        if (!empty($key)) {
            return 'git@bitbucket.org:' . $this->getProject()->getReference() . '.git';
        } else {
            return 'https://bitbucket.org/' . $this->getProject()->getReference() . '.git';
        }
    }

    /**
     * Send status updates to any relevant third parties (i.e. Github)
     */
    public function sendStatusPostback()
    {

        $key = \b8\Config::getInstance()->get('phpci.bitbucket.key');
        $secret = \b8\Config::getInstance()->get('phpci.bitbucket.secret');


        if (empty($key) || empty($secret) || empty($this->data['id'])) {
            return;
        }

        $project = $this->getProject();

        if (empty($project)) {
            return;
        }

        switch ($this->getStatus()) {
            case 0:
            case 1:
                $status = 'INPROGRESS';
                $description = 'PHPCI build running.';
                break;
            case 2:
                $status = 'SUCCESSFUL';
                $description = 'PHPCI build passed.';
                break;
            case 3:
                $status = 'FAILED';
                $description = 'PHPCI build failed.';
                break;
            default:
                $status = 'FAILED';
                $description = 'PHPCI build failed to complete.';
                break;
        }

        $phpciUrl = \b8\Config::getInstance()->get('phpci.url') . '/build/view/' . $this->getId();

        $buildRef = 'PHPCI-PROJECT-' . strtoupper(str_replace(['/', '_'], '-', $project->getReference()));

        if (\strlen($buildRef) >= 40) {
            // bitbucket has limit for build key length
            $buildRef = 'PHPCI-PROJECT-' . $this->getProjectId();
        }

        $params = [];
        if ($description != "") {
            $params["description"] = $description;
        }

        $config = array(
            'oauth_consumer_key' => $key,
            'oauth_consumer_secret' => $secret,
        );

        $client = new Client();
        $client->addListener(new NormalizeArrayListener());
        $client->addListener(new OAuthListener($config));
        $client->setApiVersion('2.0');

        $mandatory = array(
            'state' => $status,
            'key' => $buildRef,
            'url' => $phpciUrl,
        );

        $params = array_merge($mandatory, $params);

        $client->post(
            sprintf('repositories/%s/commit/%s/statuses/build', $project->getReference(), $this->getCommitId()),
            json_encode($params),
            array('Content-Type' => 'application/json')
        );
    }
}
