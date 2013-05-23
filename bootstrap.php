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

    if (is_file(dirname(__FILE__) . '/' . $file)) {
        include(dirname(__FILE__) . '/' . $file);
        return;
    }
};

spl_autoload_register($autoload, true, true);

// Define our APPLICATION_PATH, if not already defined:
if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', dirname(__FILE__) . '/');
}

// Load Composer autoloader:
require_once(APPLICATION_PATH . 'vendor/autoload.php');

// Load configuration if present:
$conf = array();
$conf['b8']['app']['namespace'] = 'PHPCI';
$conf['b8']['app']['default_controller'] = 'Index';
$conf['b8']['view']['path'] = dirname(__FILE__) . '/PHPCI/View/';

$config = new b8\Config($conf);
$request = new b8\Http\Request();
$registry = new b8\Registry($config, $request);

if (file_exists(APPLICATION_PATH . 'config.php')) {
    require(APPLICATION_PATH . 'config.php');

    $conf = $config->get(null);
    unset($conf['b8']['app']);
    unset($conf['b8']['view']);

    $conf['phpci']['url'] = $conf['install_url'];

    if (isset($conf['github_app'])) {
        $conf['phpci']['github'] = $conf['github_app'];
    }
    
    unset($conf['install_url']);
    unset($conf['github_app']);

    $dumper = new Symfony\Component\Yaml\Dumper();
    $yaml = $dumper->dump($conf);

    file_put_contents(APPLICATION_PATH . 'PHPCI/config.yml', $yaml);
    unlink(APPLICATION_PATH . 'config.php');
}

if (file_exists(APPLICATION_PATH . 'PHPCI/config.yml')) {
    $config->loadYaml(APPLICATION_PATH . 'PHPCI/config.yml');

    // Define our PHPCI_URL, if not already defined:
    if (!defined('PHPCI_URL')) {
        define('PHPCI_URL', $config->get('phpci.url', '') . '/');
    }
}
