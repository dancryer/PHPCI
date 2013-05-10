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
		$payload	= json_decode($this->getParam('payload'), true);

		try
		{
			$build		= new Build();
			$build->setProjectId($project);
			$build->setCommitId($payload['after']);
			$build->setStatus(0);
			$build->setLog('');
			$build->setCreated(new \DateTime());
			$build->setBranch(str_replace('refs/heads/', '', $payload['ref']));
		}
		catch(\Exception $ex)
		{
			header('HTTP/1.1 400 Bad Request');
			header('Ex: ' . $ex->getMessage());
			die('FAIL');
		}

		try
		{
			$this->_buildStore->save($build);
		}
		catch(\Exception $ex)
		{
			header('HTTP/1.1 500 Internal Server Error');
			header('Ex: ' . $ex->getMessage());
			die('FAIL');
		}
		
		die('OK');
	}
}