<?php

namespace PHPCI\Controller;
use b8,
	b8\Store,
	PHPCI\Model\Build;


class GithubController extends b8\Controller
{
	public function init()
	{
		$this->_buildStore		= Store\Factory::getStore('Build');
	}

	public function index()
	{
		$payload	= json_decode($this->getParam('payload'));

		$build		= new Build();
		$build->setProjectId($this->getParam('project'));
		$build->setCommitId($payload['after']);
		$build->setStatus(0);
		$build->setLog('');
		$build->setBranch(str_replace('refs/heads/', '', $payload['ref']));

		$this->_buildStore->save($build);
		die('OK');
	}
}