<?php

namespace PHPCI\Controller;
use b8;

class SessionController extends b8\Controller
{
	public function init()
	{
		$this->_userStore		= b8\Store\Factory::getStore('User');
	}

	public function login()
	{
		if(b8\Registry::getInstance()->get('requestMethod') == 'POST')
		{
			$user = $this->_userStore->getByEmail($this->getParam('email'));

			if($user && password_verify($this->getParam('password', ''), $user->getHash()))
			{
				$_SESSION['user_id']	= $user->getId();
				header('Location: /');
				die;
			}
		}

		$form = new b8\Form();
		$form->setMethod('POST');
		$form->setAction('/session/login');

		$email = new b8\Form\Element\Email('email');
		$email->setLabel('Email Address');
		$email->setRequired(true);
		$email->setClass('span3');
		$form->addField($email);

		$pwd = new b8\Form\Element\Password('password');
		$pwd->setLabel('Password');
		$pwd->setRequired(true);
		$pwd->setClass('span3');
		$form->addField($pwd);

		$pwd = new b8\Form\Element\Submit();
		$pwd->setValue('Login &raquo;');
		$pwd->setClass('btn-success');
		$form->addField($pwd);

		$view = new b8\View('Login');
		$view->form = $form->render();
		die($view->render());
	}

	public function logout()
	{
		unset($_SESSION['user_id']);
		header('Location: /');
		die;
	}
}