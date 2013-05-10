<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once('bootstrap.php');

define('PHPCI_BIN_DIR', dirname(__FILE__) . '/vendor/bin/');
define('PHPCI_DIR', dirname(__FILE__) . '/');

$store	= b8\Store\Factory::getStore('Build');
$result	= $store->getByStatus(0);

foreach($result['items'] as $build)
{
	$builder = new PHPCI\Builder($build);
	$builder->execute();
}