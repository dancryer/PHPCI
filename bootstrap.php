<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

// If composer has not been run, fail at this point and tell the user to install:
if (!file_exists(__DIR__ . '/vendor/autoload.php') && defined('PHPCI_IS_CONSOLE') && PHPCI_IS_CONSOLE) {
    $message = 'Please install PHPCI with "composer install" (or "php composer.phar install"';
    $message .= ' for Windows) before using console';

    file_put_contents('php://stderr', $message);
    exit(1);
}

// Load Composer autoloader:
require_once(__DIR__ . '/vendor/autoload.php');

// Let PHP take a guess as to the default timezone, if the user hasn't set one:
$timezone = ini_get('date.timezone');
if (empty($timezone)) {
    date_default_timezone_set('UTC');
}

use PHPCI\Logging\LoggerConfig;
use Pimple\Container;
use G\Yaml2Pimple\ContainerBuilder;
use G\Yaml2Pimple\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

$configFile = __DIR__ . '/PHPCI/config.yml';
$configEnv = getenv('phpci_config_file');

if (!empty($configEnv) && file_exists($configEnv)) {
    $configFile = $configEnv;
}

// If we don't have a config file at all, fail at this point and tell the user to install:
if (!file_exists($configFile) && (!defined('PHPCI_IS_CONSOLE') || !PHPCI_IS_CONSOLE)) {
    $message = 'PHPCI has not yet been installed - Please use the command "./console phpci:install" ';
    $message .= '(or "php ./console phpci:install" for Windows) to install it.';

    die($message);
}

\PHPCI\ErrorHandler::register();

if (defined('PHPCI_IS_CONSOLE') && PHPCI_IS_CONSOLE) {
    $loggerConfig = LoggerConfig::newFromFile(__DIR__ . "/loggerconfig.php");
}

$container = new Container();

$builder = new ContainerBuilder($container);
$loader = new YamlFileLoader($builder, new FileLocator(__DIR__));
$loader->load('services.yml');

if (file_exists($configFile)) {
    $container['config_file'] = $configFile;
}

/**
 * Allow to modify PHPCI configuration without modify versioned code.
 * Dameons should be killed to apply changes in the file.
 *
 * @ticket 781
 */
$localVarsFile = __DIR__ . '/local_vars.php';
if (is_readable($localVarsFile)) {
    require_once $localVarsFile;
}

require_once(__DIR__ . '/vars.php');

\PHPCI\Helper\Lang::init($container['config']);
