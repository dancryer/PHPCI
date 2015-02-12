<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

// Let PHP take a guess as to the default timezone, if the user hasn't set one:
use PHPCI\Logging\Handler;
use PHPCI\Logging\LoggerConfig;

$timezone = ini_get('date.timezone');
if (empty($timezone)) {
    date_default_timezone_set('UTC');
}

// If the PHPCI config file is not where we expect it, try looking in
// env for an alternative config path.
$configFile = dirname(__FILE__) . '/PHPCI/config.yml';

$configEnv = getenv('phpci_config_file');
if (!empty($configEnv)) {
    $configFile = $configEnv;
}

// If we don't have a config file at all, fail at this point and tell the user to install:
if (!file_exists($configFile) && (!defined('PHPCI_IS_CONSOLE') || !PHPCI_IS_CONSOLE)) {
    $message = 'PHPCI has not yet been installed - Please use the command "./console phpci:install" ';
    $message .= '(or "php ./console phpci:install" for Windows) to install it.';

    die($message);
}

// If composer has not been run, fail at this point and tell the user to install:
if (!file_exists(dirname(__FILE__) . '/vendor/autoload.php') && defined('PHPCI_IS_CONSOLE') && PHPCI_IS_CONSOLE) {
    $message = 'Please install PHPCI with "composer install" (or "php composer.phar install"';
    $message .= ' for Windows) before using console';
    
    file_put_contents('php://stderr', $message);
    exit(1);
}

// Load Composer autoloader:
require_once(dirname(__FILE__) . '/vendor/autoload.php');

if (defined('PHPCI_IS_CONSOLE') && PHPCI_IS_CONSOLE) {
    $loggerConfig = LoggerConfig::newFromFile(__DIR__ . "/loggerconfig.php");
    Handler::register($loggerConfig->getFor('_'));
}

// Load configuration if present:
$conf = array();
$conf['b8']['app']['namespace'] = 'PHPCI';
$conf['b8']['app']['default_controller'] = 'Home';
$conf['b8']['view']['path'] = dirname(__FILE__) . '/PHPCI/View/';

$config = new b8\Config($conf);

if (file_exists($configFile)) {
    $config->loadYaml($configFile);
}

/**
 * Allow to modify PHPCI configuration without modify versioned code.
 * Dameons should be killed to apply changes in the file.
 *
 * @ticket 781
 */
$localVarsFile = dirname(__FILE__) . '/local_vars.php';
if (is_readable($localVarsFile)) {
    require_once $localVarsFile;
}

require_once(dirname(__FILE__) . '/vars.php');

\PHPCI\Helper\Lang::init($config);
