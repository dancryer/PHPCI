<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright	Copyright 2013, Block 8 Limited.
* @license		https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link			http://www.phptesting.org/
*/

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once('../bootstrap.php');

$fc = new PHPCI\Application($config, new b8\Http\Request());
print $fc->handleRequest();

