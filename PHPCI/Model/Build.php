<?php

/**
 * Build model for table: build
 */

namespace PHPCI\Model;

require_once(APPLICATION_PATH . 'PHPCI/Model/Base/BuildBase.php');

use PHPCI\Model\Base\BuildBase;

/**
 * Build Model
 * @uses PHPCI\Model\Base\BuildBase
 */
class Build extends BuildBase
{
	public function getCommitLink()
	{
		switch($this->getProject()->getType())
		{
			case 'github':
				return 'https://github.com/' . $this->getProject()->getReference() . '/commit/' . $this->getCommitId();
		}
	}

	public function getBranchLink()
	{
		switch($this->getProject()->getType())
		{
			case 'github':
				return 'https://github.com/' . $this->getProject()->getReference() . '/tree/' . $this->getBranch();
		}
	}

	public function sendStatusPostback()
	{
		$project	= $this->getProject();

		if($project->getType() == 'github' && $project->getToken())
		{
			$url	= 'https://api.github.com/repos/'.$project->getReference().'/statuses/'.$this->build->getCommitId() . '?access_token=' . $project->getToken();
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

			$params	= array('status' => $status, 'target_url' => \b8\Registry::getInstance()->get('install_url') . '/build/view/' . $this->getId());
			$http->post($url, $params);
		}
	}
}
