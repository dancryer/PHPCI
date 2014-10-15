<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Model\Build;

use PHPCI\Builder;
use PHPCI\Model\Build\RemoteGitBuild;

/**
* Github Build Model
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Core
*/
class GithubBuild extends RemoteGitBuild
{
    /**
    * Get link to commit from another source (i.e. Github)
    */
    public function getCommitLink()
    {
        return 'https://github.com/' . $this->getProject()->getReference() . '/commit/' . $this->getCommitId();
    }

    /**
    * Get link to branch from another source (i.e. Github)
    */
    public function getBranchLink()
    {
        return 'https://github.com/' . $this->getProject()->getReference() . '/tree/' . $this->getBranch();
    }

    /**
    * Send status updates to any relevant third parties (i.e. Github)
    */
    public function sendStatusPostback()
    {
        $token = \b8\Config::getInstance()->get('phpci.github.token');

        if (empty($token)) {
            return;
        }

        $project    = $this->getProject();

        $url    = 'https://api.github.com/repos/'.$project->getReference().'/statuses/'.$this->getCommitId();
        $http   = new \b8\HttpClient();

        switch($this->getStatus())
        {
            case 0:
            case 1:
                $status = 'pending';
                break;
            case 2:
                $status = 'success';
                break;
            case 3:
                $status = 'failure';
                break;
            default:
                $status = 'error';
                break;
        }

        $phpciUrl = \b8\Config::getInstance()->get('phpci.url');
        $params = array(    'state' => $status,
                            'target_url' => $phpciUrl . '/build/view/' . $this->getId());
        $headers = array(
            'Authorization: token ' . $token,
            'Content-Type: application/x-www-form-urlencoded'
            );

        $http->setHeaders($headers);
        $http->request('POST', $url, json_encode($params));
    }

    /**
    * Get the URL to be used to clone this remote repository.
    */
    protected function getCloneUrl()
    {
        $key = trim($this->getProject()->getSshPrivateKey());

        if (!empty($key)) {
            return 'git@github.com:' . $this->getProject()->getReference() . '.git';
        } else {
            return 'https://github.com/' . $this->getProject()->getReference() . '.git';
        }
    }

    public function getCommitMessage()
    {
        $rtn = $this->data['commit_message'];

        $reference = $this->getProject()->getReference();
        $commitLink = '<a target="_blank" href="https://github.com/' . $reference . '/issues/$1">#$1</a>';
        $rtn = preg_replace('/\#([0-9]+)/', $commitLink, $rtn);
        $rtn = preg_replace('/\@([a-zA-Z0-9_]+)/', '<a target="_blank" href="https://github.com/$1">@$1</a>', $rtn);

        return $rtn;
    }

    public function getFileLinkTemplate()
    {
        $reference = $this->getProject()->getReference();
        $branch = $this->getBranch();

        if ($this->getExtra('build_type') == 'pull_request') {
            $matches = array();
            preg_match('/\/([a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-]+)/', $this->getExtra('remote_url'), $matches);

            $reference = $matches[1];
            $branch = $this->getExtra('remote_branch');
        }

        $link = 'https://github.com/' . $reference . '/';
        $link .= 'blob/' . $branch . '/';
        $link .= '{FILE}';
        $link .= '#L{LINE}';

        return $link;
    }

    protected function postCloneSetup(Builder $builder, $cloneTo)
    {
        $buildType = $this->getExtra('build_type');

        $success = true;

        try {
            if (!empty($buildType) && $buildType == 'pull_request') {
                $remoteUrl = $this->getExtra('remote_url');
                $remoteBranch = $this->getExtra('remote_branch');

                $cmd = 'cd "%s" && git checkout -b phpci/' . $this->getId() . ' %s && git pull -q --no-edit %s %s';
                $success = $builder->executeCommand($cmd, $cloneTo, $this->getBranch(), $remoteUrl, $remoteBranch);
            }
        } catch (\Exception $ex) {
            $success = false;
        }

        if ($success) {
            $success = parent::postCloneSetup($builder, $cloneTo);
        }

        return $success;
    }
}
