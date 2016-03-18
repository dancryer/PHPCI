<?php

// Define our APPLICATION_PATH, if not already defined:
if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', dirname(__FILE__) . '/');
    define('PHPCI_DIR', APPLICATION_PATH);
}

// Define our PHPCI_URL, if not already defined:
if (!defined('PHPCI_URL') && isset($config)) {
    define('PHPCI_URL', $config->get('phpci.url', '') . '/');
}

// Define PHPCI_BIN_DIR
if (!defined('PHPCI_BIN_DIR')) {
    define('PHPCI_BIN_DIR', PHPCI_DIR . 'vendor/bin/');
}

// Define PHPCI_BUILD_ROOT_DIR
if (!defined('PHPCI_BUILD_ROOT_DIR')) {
    define('PHPCI_BUILD_ROOT_DIR', PHPCI_DIR . 'PHPCI' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR);
}

// Should PHPCI run the Shell plugin?
if (!defined('ENABLE_SHELL_PLUGIN')) {
    define('ENABLE_SHELL_PLUGIN', false);
}

// If this is not already defined, we're not running in the console:
if (!defined('PHPCI_IS_CONSOLE')) {
    define('PHPCI_IS_CONSOLE', false);
}

if (!defined('IS_WIN')) {
    define('IS_WIN', ((strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? true : false));
}

// If an environment variable is set defining our config location, use that
// otherwise fall back to PHPCI/config.yml.
if (!defined('PHPCI_CONFIG_FILE')) {
    define('PHPCI_CONFIG_FILE', $configFile);
}
