<?php

/**
 * Build model for table: build
 */

namespace PHPCI\Model\Build;
use PHPCI\Model\Build\RemoteGitBuild;

/**
 * Build Model
 * @uses PHPCI\Model\Build
 */
class GithubBuild extends RemoteGitBuild
{
	public function getCommitLink()
	{
		return 'https://github.com/' . $this->getProject()->getReference() . '/commit/' . $this->getCommitId();
	}

	public function getBranchLink()
	{
		return 'https://github.com/' . $this->getProject()->getReference() . '/tree/' . $this->getBranch();
	}

	public function sendStatusPostback()
	{
		$project	= $this->getProject();

		// The postback will only work if we have an access token.
		if(!$project->getToken()) {
			return;
		}

		$url	= 'https://api.github.com/repos/'.$project->getReference().'/statuses/'.$this->getCommitId();
		$http	= new \b8\HttpClient();

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

		$params	= array(	'state' => $status, 
							'target_url' => \b8\Registry::getInstance()->get('install_url') . '/build/view/' . $this->getId());

		$http->setHeaders(array('Authorization: token ' . $project->getToken()));
		$http->request('POST', $url, json_encode($params));
	}

	protected function getCloneUrl()
	{
		$key = trim($this->getProject()->getGitKey());

		if(!empty($key)) {
			return 'git@github.com:' . $this->getProject()->getReference() . '.git';
		}
		else {
			return 'https://github.com/' . $this->getProject()->getReference() . '.git';
		}
	}
}
