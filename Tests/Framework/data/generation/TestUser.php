<?php

require_once(B8_PATH . 'Type/RestUser.php');

use PHPCI\Framework\Type\RestUser;

class TestUser implements RestUser
{
	public function checkPermission($permission, $resource)
	{
		return $resource == 'unos' || $resource == 'tress' ? true : false;
	}
}