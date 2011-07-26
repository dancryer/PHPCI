<?php

if (!$iniPath = get_cfg_var('cfg_file_path')) {
    $iniPath = 'WARNING: not using a php.ini file';
}

echo "********************************\n";
echo "*                              *\n";
echo "*  Symfony requirements check  *\n";
echo "*                              *\n";
echo "********************************\n\n";
echo sprintf("php.ini used by PHP: %s\n\n", $iniPath);

echo "** WARNING **\n";
echo "*  The PHP CLI can use a different php.ini file\n";
echo "*  than the one used with your web server.\n";
if ('\\' == DIRECTORY_SEPARATOR) {
    echo "*  (especially on the Windows platform)\n";
}
echo "*  If this is the case, please ALSO launch this\n";
echo "*  utility from your web server.\n";
echo "** WARNING **\n";

// mandatory
echo_title("Mandatory requirements");
check(version_compare(phpversion(), '5.3.2', '>='), sprintf('Checking that PHP version is at least 5.3.2 (%s installed)', phpversion()), 'Install PHP 5.3.2 or newer (current version is '.phpversion(), true);
check(ini_get('date.timezone'), 'Checking that the "date.timezone" setting is set', 'Set the "date.timezone" setting in php.ini (like Europe/Paris)', true);
check(is_writable(__DIR__.'/../app/cache'), sprintf('Checking that app/cache/ directory is writable'), 'Change the permissions of the app/cache/ directory so that the web server can write in it', true);
check(is_writable(__DIR__.'/../app/logs'), sprintf('Checking that the app/logs/ directory is writable'), 'Change the permissions of the app/logs/ directory so that the web server can write in it', true);
check(function_exists('json_encode'), 'Checking that the json_encode() is available', 'Install and enable the json extension', true);
check(class_exists('SQLite3') || in_array('sqlite', PDO::getAvailableDrivers()), 'Checking that the SQLite3 or PDO_SQLite extension is available', 'Install and enable the SQLite3 or PDO_SQLite extension.', true);
check(function_exists('session_start'), 'Checking that the session_start() is available', 'Install and enable the session extension', true);
check(function_exists('ctype_alpha'), 'Checking that the ctype_alpha() is available', 'Install and enable the ctype extension', true);

// warnings
echo_title("Optional checks");
check(class_exists('DomDocument'), 'Checking that the PHP-XML module is installed', 'Install and enable the php-xml module', false);
check(defined('LIBXML_COMPACT'), 'Checking that the libxml version is at least 2.6.21', 'Upgrade your php-xml module with a newer libxml', false);
check(function_exists('token_get_all'), 'Checking that the token_get_all() function is available', 'Install and enable the Tokenizer extension (highly recommended)', false);
check(function_exists('mb_strlen'), 'Checking that the mb_strlen() function is available', 'Install and enable the mbstring extension', false);
check(function_exists('iconv'), 'Checking that the iconv() function is available', 'Install and enable the iconv extension', false);
check(function_exists('utf8_decode'), 'Checking that the utf8_decode() is available', 'Install and enable the XML extension', false);
if (PHP_OS != 'WINNT') {
    check(function_exists('posix_isatty'), 'Checking that the posix_isatty() is available', 'Install and enable the php_posix extension (used to colorized the CLI output)', false);
}
check(class_exists('Locale'), 'Checking that the intl extension is available', 'Install and enable the intl extension (used for validators)', false);
if (class_exists('Locale')) {
    $version = '';

    if (defined('INTL_ICU_VERSION')) {
        $version =  INTL_ICU_VERSION;
    } else {
        $reflector = new \ReflectionExtension('intl');

        ob_start();
        $reflector->info();
        $output = strip_tags(ob_get_clean());

        preg_match('/^ICU version +(?:=> )?(.*)$/m', $output, $matches);
        $version = $matches[1];
    }

    check(version_compare($version, '4.0', '>='), 'Checking that the intl ICU version is at least 4+', 'Upgrade your intl extension with a newer ICU version (4+)', false);
}

$accelerator = 
    (function_exists('apc_store') && ini_get('apc.enabled'))
    ||
    function_exists('eaccelerator_put') && ini_get('eaccelerator.enable')
    ||
    function_exists('xcache_set')
;
check($accelerator, 'Checking that a PHP accelerator is installed', 'Install a PHP accelerator like APC (highly recommended)', false);
check(function_exists('apc_store') && ini_get('apc.enabled') && version_compare(phpversion('apc'), '3.0.17', '>='), 'Checking that the APC version is at least 3.0.17', 'Upgrade your APC extension (3.0.17+)', true);

check(!ini_get('short_open_tag'), 'Checking that php.ini has short_open_tag set to off', 'Set short_open_tag to off in php.ini', false);
check(!ini_get('magic_quotes_gpc'), 'Checking that php.ini has magic_quotes_gpc set to off', 'Set magic_quotes_gpc to off in php.ini', false);
check(!ini_get('register_globals'), 'Checking that php.ini has register_globals set to off', 'Set register_globals to off in php.ini', false);
check(!ini_get('session.auto_start'), 'Checking that php.ini has session.auto_start set to off', 'Set session.auto_start to off in php.ini', false);

echo_title("Optional checks (Doctrine)");

check(class_exists('PDO'), 'Checking that PDO is installed', 'Install PDO (mandatory for Doctrine)', false);
if (class_exists('PDO')) {
    $drivers = PDO::getAvailableDrivers();
    check(count($drivers), 'Checking that PDO has some drivers installed: '.implode(', ', $drivers), 'Install PDO drivers (mandatory for Doctrine)');
}

/**
 * Checks a configuration.
 */
function check($boolean, $message, $help = '', $fatal = false)
{
    echo $boolean ? "  OK        " : sprintf("\n\n[[%s]] ", $fatal ? ' ERROR ' : 'WARNING');
    echo sprintf("$message%s\n", $boolean ? '' : ': FAILED');

    if (!$boolean) {
        echo "            *** $help ***\n";
        if ($fatal) {
            die("You must fix this problem before resuming the check.\n");
        }
    }
}

function echo_title($title)
{
    echo "\n** $title **\n\n";
}
