<?php

namespace PHPCI\Controller;
use b8,
	b8\Store,
	PHPCI\Model\Build;


class BitbucketController extends b8\Controller
{
	public function init()
	{
		$this->_buildStore		= Store\Factory::getStore('Build');
	}

	public function webhook($project)
	{
		$payload	= json_decode(file_get_contents('php://input'), true);
		$branches	= array();
		$commits	= array();

		foreach($payload['commits'] as $commit)
		{
			if(!in_array($commit['branch'], $branches))
			{
				$branches[]					= $commit['branch'];
				$commits[$commit['branch']]	= $commit['raw_node'];
			}
		}

		foreach($branches as $branch)
		{
			try
			{

				$build		= new Build();
				$build->setProjectId($project);
				$build->setCommitId($commits[$branch]);
				$build->setStatus(0);
				$build->setLog('');
				$build->setCreated(new \DateTime());
				$build->setBranch($branch);
				$this->_buildStore->save($build);
			}
			catch(\Exception $ex)
			{
			}
		}
		
		die('OK');
	}
}