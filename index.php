<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright	Copyright 2013, Block 8 Limited.
* @license		https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link			http://www.phptesting.org/
*/

session_start();

require_once('bootstrap.php');

$fc = new PHPCI\Application();
print $fc->handleRequest();
