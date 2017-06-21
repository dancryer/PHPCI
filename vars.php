<?php

// Define our APPLICATION_PATH, if not already defined:
if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', dirname(__FILE__) . '/');
    define('KIBOKO_CI_APP_DIR', APPLICATION_PATH);
}

// Define our KIBOKO_CI_APP_URL, if not already defined:
if (!defined('KIBOKO_CI_APP_URL') && isset($config)) {
    define('KIBOKO_CI_APP_URL', $config->get('phpci.url', '') . '/');
}

// Define KIBOKO_CI_APP_BIN_DIR
if (!defined('KIBOKO_CI_APP_BIN_DIR')) {
    define('KIBOKO_CI_APP_BIN_DIR', KIBOKO_CI_APP_DIR . 'vendor/bin/');
}

// Define KIBOKO_CI_APP_BUILD_ROOT_DIR
if (!defined('KIBOKO_CI_APP_BUILD_ROOT_DIR')) {
    define('KIBOKO_CI_APP_BUILD_ROOT_DIR', KIBOKO_CI_APP_DIR . 'src/build/');
}

// Should Kiboko CI run the Shell plugin?
if (!defined('ENABLE_SHELL_PLUGIN')) {
    define('ENABLE_SHELL_PLUGIN', false);
}

// If this is not already defined, we're not running in the console:
if (!defined('KIBOKO_CI_APP_IS_CONSOLE')) {
    define('KIBOKO_CI_APP_IS_CONSOLE', false);
}

if (!defined('IS_WIN')) {
    define('IS_WIN', ((strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? true : false));
}

// If an environment variable is set defining our config location, use that
// otherwise fall back to src/config.yml.
if (!defined('KIBOKO_CI_APP_CONFIG_FILE')) {
    define('KIBOKO_CI_APP_CONFIG_FILE', $configFile);
}
