<?php
/**
* Kiboko CI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

// Let PHP take a guess as to the default timezone, if the user hasn't set one:
date_default_timezone_set(@date_default_timezone_get());

// Load Composer autoloader:
require_once(dirname(__DIR__) . '/vendor/autoload.php');

// If the Kiboko CI config file is not where we expect it, try looking in
// env for an alternative config path.
$configFile = dirname(__FILE__) . '/../src/config.yml';

if (!file_exists($configFile)) {
    $configEnv = getenv('phpci_config_file');

    if (!empty($configEnv)) {
        $configFile = $configEnv;
    }
}

// Load configuration if present:
$conf = array();
$conf['b8']['app']['namespace'] = 'Kiboko\\Component\\ContinuousIntegration';
$conf['b8']['app']['default_controller'] = 'Home';
$conf['b8']['view']['path'] = dirname(__DIR__) . '/src/ContinuousIntegration/View/';

$config = new b8\Config($conf);

if (file_exists($configFile)) {
    $config->loadYaml($configFile);
}

require_once(dirname(__DIR__) . '/vars.php');

\Kiboko\Component\ContinuousIntegration\Helper\Lang::init($config);
\Kiboko\Component\ContinuousIntegration\Helper\Lang::setLanguage("en");
