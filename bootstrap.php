<?php

date_default_timezone_set(@date_default_timezone_get());

spl_autoload_register(function ($class)
{
	$file = str_replace(array('\\', '_'), '/', $class);
	$file .= '.php';

	if(substr($file, 0, 1) == '/')
	{
		$file = substr($file, 1);
	}

	if(is_file(dirname(__FILE__) . '/' . $file))
	{
		include(dirname(__FILE__) . '/' . $file);

		return;
	}
}, true, true);

define('APPLICATION_PATH', dirname(__FILE__) . '/');

require_once('vendor/autoload.php');
require('config.php');

b8\Registry::getInstance()->set('app_namespace', 'PHPCI');
b8\Registry::getInstance()->set('DefaultController', 'Index');
b8\Registry::getInstance()->set('ViewPath', dirname(__FILE__) . '/PHPCI/View/');