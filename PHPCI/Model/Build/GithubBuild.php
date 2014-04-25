<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Model\Build;

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
        $key = trim($this->getProject()->getGitKey());

        if (!empty($key)) {
            return 'git@github.com:' . $this->getProject()->getReference() . '.git';
        } else {
            return 'https://github.com/' . $this->getProject()->getReference() . '.git';
        }
    }

    public function getCommitMessage()
    {
        $rtn = $this->data['commit_message'];

        $rtn = preg_replace('/\#([0-9]+)/', '<a target="_blank" href="https://github.com/' . $this->getProject()->getReference() . '/issues/$1">#$1</a>', $rtn);
        $rtn = preg_replace('/\@([a-zA-Z0-9_]+)/', '<a target="_blank" href="https://github.com/$1">@$1</a>', $rtn);

        return $rtn;
    }

    public function getFileLinkTemplate()
    {
        $link = 'https://github.com/' . $this->getProject()->getReference() . '/';
        $link .= 'blob/' . $this->getBranch() . '/';
        $link .= '{FILE}';
        $link .= '#L{LINE}';

        return $link;
    }
}
