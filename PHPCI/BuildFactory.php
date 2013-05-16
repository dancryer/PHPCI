<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright	Copyright 2013, Block 8 Limited.
* @license		https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link			http://www.phptesting.org/
*/

namespace PHPCI;
use PHPCI\Model\Build,
	PHPCI\Model\Build\LocalBuild,
	PHPCI\Model\Build\GithubBuild,
	PHPCI\Model\Build\BitbucketBuild;

/**
* PHPCI Build Factory - Takes in a generic "Build" and returns a type-specific build model.
* @author	Dan Cryer <dan@block8.co.uk>
*/
class BuildFactory
{
	public static function getBuild(Build $base)
	{
		switch($base->getProject()->getType())
		{
			case 'local':
				$type = 'LocalBuild';
			break;

			case 'github':
				$type = 'GithubBuild';
			break;

			case 'bitbucket':
				$type = 'BitbucketBuild';
			break;
		}

		$type = '\\PHPCI\\Model\\Build\\' . $type;

		return new $type($base->getDataArray());
	}
}