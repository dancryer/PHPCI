<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Model\Build;

use Kiboko\Component\ContinuousIntegration\Model\Build;
use Kiboko\Component\ContinuousIntegration\Model\Build\RemoteGitBuild;

/**
* BitBucket Build Model
* @author       Dan Cryer <dan@block8.co.uk>
* @package      Kiboko\Component\ContinuousIntegration
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
}
