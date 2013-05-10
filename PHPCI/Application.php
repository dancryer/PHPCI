<?php

namespace PHPCI;
use b8,
	b8\Registry;

class Application extends b8\Application
{
	public function handleRequest()
	{
		$controllerName = \b8\Registry::getInstance()->get('ControllerName');

		if(!in_array($controllerName, array('Bitbucket', 'Github')) && !($controllerName == 'Session' && in_array($this->action, array('login', 'logout'))))
		{
			$this->validateSession();
		}

		$view           = new b8\View('Layout');
		$view->content  = parent::handleRequest();

		return $view->render();
	}

	protected function validateSession()
	{
		if(!empty($_SESSION['user_id']))
		{
			$user = b8\Store\Factory::getStore('User')->getByPrimaryKey($_SESSION['user_id']);

			if($user)
			{
				Registry::getInstance()->set('user', $user);
				return;
			}

			unset($_SESSION['user_id']);
		}

		header('Location: /session/login');
		die;
	}
}