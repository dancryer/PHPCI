<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2013, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         http://www.phptesting.org/
 */

namespace PHPCI\Controller;

use b8;
use b8\Store;
use PHPCI\Model\Build;

/**
 * Api Controller - give informations to other apps.
 * @author       André Cianfarani <acianfa@gmail.com>
 * @package      PHPCI
 * @subpackage   Web
 */
class ApiController extends \PHPCI\Controller
{
	var $wsMethods = array("getProjects");

	public function init()
	{
		$this->_projectStore      = Store\Factory::getStore('Project');
	}

	/**
	 * Called by other apps:
	 */
	public function projects()
	{
		$this -> checkMethod(array("GET"));

		$projects = $this->_projectStore->getWhere(array(), null, null, array(), array('title' => 'ASC'));
		$res = array();
		foreach ($projects["items"] as $project) {
			$entry = array("id"=>$project->getId(), "title"=>$project->getTitle(), "type"=>$project->getType(), "access_information" => $this->getUrl($project));
			$res[] = $entry;

		}
		echo json_encode($res);
		die();
	}

	protected function checkMethod($allowed) {
		$method = $this->request->getMethod();
		if (! in_array($method, $allowed)) {
			die($method.' bad method according action to perform');
		}
	}
	protected function getUrl($project) {
		$key = trim($project->getGitKey());

		switch($project->getType())
		{
			case 'github':
				if (!empty($key)) {
					return 'git@github.com:' . $project->getReference() . '.git';
				} else {
					return 'https://github.com/' . $project->getReference() . '.git';
				}
				break;
			case 'bitbucket':
				if (!empty($key)) {
					return 'git@bitbucket.org:' . $this->getProject()->getReference() . '.git';
				} else {
					return 'https://bitbucket.org/' . $this->getProject()->getReference() . '.git';
				}
				break;
			case 'gitlab':
				if (!empty($key)) {
					return $project->getAccessInformation()["user"].'@'.$project->getAccessInformation()["domain"].':' . $project->getReference() . '.git';
				}
				break;
			default :
				return $project->getReference();
				break;
		}
	}
}
