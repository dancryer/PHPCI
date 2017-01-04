<?php

namespace PHPCI\Framework\Type;

interface RestUser
{
	public function checkPermission($permission, $resource);
}