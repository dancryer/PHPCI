<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Model\Build;

use PHPCI\Model\Build;
use PHPCI\Model\Build\RemoteGitBuild;

/**
* BitBucket Build Model
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Core
*/
class BitbucketBuild extends RemoteGitBuild
{
    public function getCommitLink()
    {
        return 'https://bitbucket.org/' . $this->getProject()->getReference() . '/commits/' . $this->getCommitId();
    }

    public function getBranchLink()
    {
        return 'https://bitbucket.org/' . $this->getProject()->getReference() . '/src/?at=' . $this->getBranch();
    }

    protected function getCloneUrl()
    {
        $key = trim($this->getProject()->getGitKey());

        if (!empty($key)) {
            return 'git@bitbucket.org:' . $this->getProject()->getReference() . '.git';
        } else {
            return 'https://bitbucket.org/' . $this->getProject()->getReference() . '.git';
        }
    }
}
