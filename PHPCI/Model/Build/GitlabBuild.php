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
* Gitlab Build Model
* @author       André Cianfarani <a.cianfarani@c2is.fr>
* @package      PHPCI
* @subpackage   Core
*/
class GitlabBuild extends RemoteGitBuild
{

    /**
    * Get link to commit from another source (i.e. Github)
    */
    public function getCommitLink()
    {
        return 'http://'.$this->getProject()->getAccessInformation()["domain"].'/' . $this->getProject()->getReference() . '/commit/' . $this->getCommitId();
    }

    /**
    * Get link to branch from another source (i.e. Github)
    */
    public function getBranchLink()
    {
        return 'http://'.$this->getProject()->getAccessInformation()["domain"].'/' . $this->getProject()->getReference() . '/tree/' . $this->getBranch();
    }

    /**
    * Get the URL to be used to clone this remote repository.
    */
    protected function getCloneUrl()
    {
        $key = trim($this->getProject()->getGitKey());

        if (!empty($key)) {
            return $this->getProject()->getAccessInformation()["user"].'@'.$this->getProject()->getAccessInformation()["domain"].':' . $this->getProject()->getReference() . '.git';
        }
    }
}
