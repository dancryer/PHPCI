<?php

namespace PHPCI;
use PHPCI\Model\Build,
	PHPCI\Model\Build\LocalBuild,
	PHPCI\Model\Build\GithubBuild,
	PHPCI\Model\Build\BitbucketBuild;

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