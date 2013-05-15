<?php

/**
 * Build model for table: build
 */

namespace PHPCI\Model;

require_once(APPLICATION_PATH . 'PHPCI/Model/Base/BuildBase.php');

use PHPCI\Model\Base\BuildBase,
	PHPCI\Builder;

/**
 * Build Model
 * @uses PHPCI\Model\Base\BuildBase
 */
class Build extends BuildBase
{
	public function getCommitLink()
	{
		return '#';
	}

	public function getBranchLink()
	{
		return '#';
	}

	public function sendStatusPostback()
	{
		return;
	}

	public function createWorkingCopy(Builder $builder, $buildPath)
	{
	}
}
