<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once('bootstrap.php');

$store	= b8\Store\Factory::getStore('Build');
$result	= $store->getByStatus(0);

foreach($result['items'] as $build)
{
	$builder = new PHPCI\Builder($build);
	$builder->execute();
}