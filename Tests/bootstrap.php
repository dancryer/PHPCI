<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

// Let PHP take a guess as to the default timezone, if the user hasn't set one:
date_default_timezone_set(@date_default_timezone_get());

// Set up a basic autoloader for PHPCI:
$autoload = function ($class) {
    $file = str_replace(array('\\', '_'), '/', $class);
    $file .= '.php';

    if (substr($file, 0, 1) == '/') {
        $file = substr($file, 1);
    }

    if (is_file(dirname(__DIR__) . '/' . $file)) {
        include(dirname(__DIR__) . '/' . $file);
        return;
    }
};

spl_autoload_register($autoload, true, true);

// Load Composer autoloader:
require_once(dirname(__DIR__) . '/vendor/autoload.php');

// Load configuration if present:
$conf = array();
$conf['b8']['app']['namespace'] = 'PHPCI';
$conf['b8']['app']['default_controller'] = 'Home';
$conf['b8']['view']['path'] = dirname(__DIR__) . '/PHPCI/View/';

if (file_exists(dirname(__DIR__) . '/PHPCI/config.yml')) {
    $config = new b8\Config($conf);
    $config->loadYaml(dirname(__DIR__) . '/PHPCI/config.yml');
}

require_once(dirname(__DIR__) . '/vars.php');
