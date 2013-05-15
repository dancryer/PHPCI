<?php

/**
 * Build model for table: build
 */

namespace PHPCI\Model\Build;
use PHPCI\Model\Build;
use PHPCI\Model\Build\RemoteGitBuild;

/**
 * Build Model
 * @uses PHPCI\Model\Build
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

		if(!empty($key)) {
			return 'git@bitbucket.org:' . $this->getProject()->getReference() . '.git';
		}
		else {
			return 'https://bitbucket.org/' . $this->getProject()->getReference() . '.git';
		}
	}
}
