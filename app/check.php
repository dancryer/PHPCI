<?php

if (is_cli()) {
    echo "********************************\n";
    echo "*                              *\n";
    echo "*  Symfony requirements check  *\n";
    echo "*                              *\n";
    echo "********************************\n\n";
    echo sprintf("php.ini used by PHP: %s\n\n", get_ini_path());

    echo "** WARNING **\n";
    echo "*  The PHP CLI can use a different php.ini file\n";
    echo "*  than the one used with your web server.\n";
    if ('\\' == DIRECTORY_SEPARATOR) {
        echo "*  (especially on the Windows platform)\n";
    }
    echo "*  If this is the case, please ALSO launch this\n";
    echo "*  utility from your web server.\n";
    echo "** WARNING **\n";
} else {
    echo <<<EOF
<html>
<head>
<style>
body {
    padding: 30px;
    text-align: center;
    width: 900px;
    font-family: Arial;
}
</style>
</head>
<body>
<h1><img alt="symfony" style="vertical-align: middle" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAAA2CAMAAAC1HibFAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADBQTFRFpJiKd2RP0svE9PLwa1dAVD0j3djT6OXiv7atgnFdYEoxjX5sr6SYmYt7////STAUazUpiQAAA91JREFUeNrcmteWgyAQQOkg9f//dlWkW4ImZg0Pe9Yow1wZpiDATo0D4h7aiOEzApj/uEc3sIBg4h7eCJ5BHs8xkkwgxv1AAxbwm4YSjH5SPAc3TYi29qMk5ia/CyfH8kkScpPn9b5++OQquYVD2Y+Hq3tA2MSB3ONB5Dwh8PkgZl7q7vkg+oaErhE/aIQtRzq6ZQjGVpjF9MPSj3iPqhCzDKmwtAUbJQAZO6AJRIydwhOOKjEOw/KHgjBpxPg8QnlgkHs6rIIo7yfntQnz3DJ/aL69ZGtTbBhCJz51gVGEoDlItt4pwGkYklK/URjVduUW3NFhDYQimzdND0CmrJNRk3VR/vGlhQhYgRBWDGNSCstgeUudAyHYlm1WZBtEzs/zEr4UsAbSDCNcFFY3dQaE8kYO2gWhzB410IKQVmOxLYycABGLoRsIB+1HQ3TXtGjUSQxwSHY5XXlpmEYQhhDSSWGUDTPnLuk9jt2hErkxdoHAbJ6dX3TmeLHnFmAKe1Dpf5TFQ7+G8JJ20Yx3EcZIYeiwG2R5oWGI0YGRQ681lWYJfhGh8xlGFYifxKy6FskEvTCalX1BdheIDEaq10r7LSHz/yEYDO0Vr0BMk62g+FitnY53Ohe7CL5GdoJECaX4eJWBoHwGs0SMrGhH4nVvHAlGjodzIO4YBCdXVEyJWdMuzl4viIO4Na/3gjQK5APUN9lpkOTNk3m9H4S/CoLOg8Rokszr/SD4HAjqA1n8fzIv0yzPy6ZF21f3AgjrBHGEFzkfTBH6DSA8zwVdWv/DLggt/PqrICkJxjD2AW8C0c0iMWmSNkE8v34dROahKLwoUcXiSyCDrSzVZyLC7YOAejtpH0TFF69wSj59EpZIxBWQZYdLV6UDPACRVXwDuyA61oWhhBN5FubL0lAOngWBef0pQVE5boOEek3MOkBkX0njEYqlK3VFtj5m4jFxPwsSzRYjFIJWcCU7II4lHXilQw3SVDbRmlZqofMgMVK14+yBrBVeR0ljYCfbRfAVEKc3OHZB1ki2k0bcbD2sb0tcAynHQdS9BFJssByApH0arGX9YSDMFy9CwSmQcZxg5wLuBIe6nwylsddhPyASCAxc/2gCh/GW9KHg8o60hAYM/dvBs3rSu2N8cctUrJSR938zrBPAfhBo29ro7uY3wdQlEL9rg77K4R0YvrQbvzhB+FUQYdscGpziEF/l0Hble0snyGCb6uRLC6Q+eNL7eXrA3z+8MucZqvqt+8AAwd8/hDOSqPrrXv8RDvIPDuGQ5os9B08/rBUC5A8dc/qdg2c/cxTw4Yczl4rgT4ABAEs+U8HAd4FjAAAAAElFTkSuQmCC" />
REQUIREMENTS CHECK</h1>
EOF
;
    echo sprintf("<p><small>php.ini used by PHP: %s</small></p>", get_ini_path());
}

// mandatory
echo_title("Mandatory requirements");
check(version_compare(phpversion(), '5.3.2', '>='), sprintf('Checking that PHP version is at least 5.3.2 (%s installed)', phpversion()), 'Install PHP 5.3.1 or newer (current version is '.phpversion(), true);
check(ini_get('date.timezone'), 'Checking that the "date.timezone" setting is set', 'Set the "date.timezone" setting in php.ini (like Europe/Paris)', true);
check(is_writable(__DIR__.'/../app/cache'), sprintf('Checking that app/cache/ directory is writable'), 'Change the permissions of the app/cache/ directory so that the web server can write in it', true);
check(is_writable(__DIR__.'/../app/logs'), sprintf('Checking that the app/logs/ directory is writable'), 'Change the permissions of the app/logs/ directory so that the web server can write in it', true);
check(function_exists('json_encode'), 'Checking that the json_encode() is available', 'Install and enable the json extension', true);

// warnings
echo_title("Optional checks");
check(class_exists('DomDocument'), 'Checking that the PHP-XML module is installed', 'Install and enable the php-xml module', false);
check(defined('LIBXML_COMPACT'), 'Checking that the libxml version is at least 2.6.21', 'Upgrade your php-xml module with a newer libxml', false);
check(function_exists('token_get_all'), 'Checking that the token_get_all() function is available', 'Install and enable the Tokenizer extension (highly recommended)', false);
check(function_exists('mb_strlen'), 'Checking that the mb_strlen() function is available', 'Install and enable the mbstring extension', false);
check(function_exists('iconv'), 'Checking that the iconv() function is available', 'Install and enable the iconv extension', false);
check(function_exists('utf8_decode'), 'Checking that the utf8_decode() is available', 'Install and enable the XML extension', false);
check(function_exists('posix_isatty'), 'Checking that the posix_isatty() is available', 'Install and enable the php_posix extension (used to colorized the CLI output)', false);
check(class_exists('Locale'), 'Checking that the intl extension is available', 'Install and enable the intl extension (used for validators)', false);

$accelerator = 
    (function_exists('apc_store') && ini_get('apc.enabled'))
    ||
    function_exists('eaccelerator_put') && ini_get('eaccelerator.enable')
    ||
    function_exists('xcache_set')
;
check($accelerator, 'Checking that a PHP accelerator is installed', 'Install a PHP accelerator like APC (highly recommended)', false);

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

if (!is_cli()) {
  echo '</body></html>';
}

/**
 * Checks a configuration.
 */
function check($boolean, $message, $help = '', $fatal = false)
{
    if (is_cli()) {
        echo $boolean ? "  OK        " : sprintf("\n\n[[%s]] ", $fatal ? ' ERROR ' : 'WARNING');
        echo sprintf("$message%s\n", $boolean ? '' : ': FAILED');

        if (!$boolean) {
            echo "            *** $help ***\n";
            if ($fatal) {
                die("You must fix this problem before resuming the check.\n");
            }
        }
    } else {
        if ($boolean) {
            $color = '#60b111';
            $image = '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';
            $alt = 'ok';
        } elseif ($fatal) {
            $color = '#fd3900';
            $image = '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';
            $alt = 'fatal';
        } else {
            $color = '#6a9ee6';
            $image = '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';
            $alt = 'warning';
        }

        echo sprintf('
<div style="background-color: %s; padding: 4px; margin: 3px; border: 1px #ddd solid; font-size: 18px">
    <div style="float: left"><img alt="%s" style="width: 60%%; vertical-align: middle; margin-right: 10px" src="data:image/png;base64,%s" /></div>
    <div style="float: left; margin-top: 7px; text-align: left;">%s%s</div>
    <div style="clear: both"></div>
</div>', $color, $alt, $image, $message, !$boolean ? '<div style="background-color: #fff; padding:5px">What to do'.($fatal ? '' : ' (<em>optional</em>)').': '.$help.'</div>' : '');
    }
}

function echo_title($title)
{
    if (is_cli()) {
        echo "\n** $title **\n\n";
    } else {
        echo "<h2>$title</h2>";
    }
}

/**
 * Gets the php.ini path used by the current PHP interpretor.
 *
 * @return string the php.ini path
 */
function get_ini_path()
{
    if ($path = get_cfg_var('cfg_file_path')) {
        return $path;
    }

    return 'WARNING: not using a php.ini file';
}

function is_cli()
{
    return !isset($_SERVER['HTTP_HOST']);
}
