<?php

namespace PHPCI;
use b8,
	b8\Registry;

class Application extends b8\Application
{
	public function handleRequest()
	{
		$view           = new b8\View('Layout');
		$view->content  = parent::handleRequest();

		return $view->render();
	}
}