<?php

namespace PHPCI\Helper;

class User
{
	public function __call($method, $params = array())
	{
		$user = \b8\Registry::getInstance()->get('user');
		return call_user_func_array(array($user, $method), $params);
	}
}