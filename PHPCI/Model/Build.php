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
}
