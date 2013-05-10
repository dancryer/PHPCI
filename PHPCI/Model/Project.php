<?php

/**
 * Project model for table: project
 */

namespace PHPCI\Model;

require_once(APPLICATION_PATH . 'PHPCI/Model/Base/ProjectBase.php');

use PHPCI\Model\Base\ProjectBase;

/**
 * Project Model
 * @uses PHPCI\Model\Base\ProjectBase
 */
class Project extends ProjectBase
{
	public function getGitUrl()
	{
		$key = $this->getGitKey();

		switch($this->getType() . '.' . (!empty($key) ? 'ssh' : 'http'))
		{
			case 'github.ssh':
				return 'git@github.com:' . $this->getReference() . '.git';

			case 'github.http':
				return 'https://github.com/' . $this->getReference() . '.git';

			case 'bitbucket.ssh':
				return 'git@bitbucket.org:' . $this->getReference() . '.git';

			case 'bitbucket.http':
				return 'https://bitbucket.org/' . $this->getReference() . '.git';
		}
	}
}
