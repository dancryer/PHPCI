<?php

if (!isset($_SERVER['HTTP_HOST'])) {
    exit('This script cannot be run from the CLI. Run it from a browser.');
}

if (!in_array(@$_SERVER['REMOTE_ADDR'], array(
    '127.0.0.1',
    '::1',
))) {
    header('HTTP/1.0 403 Forbidden');
    exit('This script is only accessible from localhost.');
}

$majorProblems = array();
$minorProblems = array();
$phpini = false;

// minimum
if (!version_compare(phpversion(), '5.3.2', '>=')) {
    $version = phpversion();
    $majorProblems[] = <<<EOF
        You are running PHP version "<strong>$version</strong>", but Symfony
        needs at least PHP "<strong>5.3.2</strong>" to run. Before using Symfony, install
        PHP "<strong>5.3.2</strong>" or newer.
EOF;
}

if (!is_writable(__DIR__ . '/../app/cache')) {
    $majorProblems[] = 'Change the permissions of the "<strong>app/cache/</strong>"
        directory so that the web server can write into it.';
}

if (!is_writable(__DIR__ . '/../app/logs')) {
    $majorProblems[] = 'Change the permissions of the "<strong>app/logs/</strong>"
        directory so that the web server can write into it.';
}

// extensions
if (!class_exists('DomDocument')) {
    $minorProblems[] = 'Install and enable the <strong>php-xml</strong> module.';
}

if (!((function_exists('apc_store') && ini_get('apc.enabled')) || function_exists('eaccelerator_put') && ini_get('eaccelerator.enable') || function_exists('xcache_set'))) {
    $minorProblems[] = 'Install and enable a <strong>PHP accelerator</strong> like APC (highly recommended).';
}

if (!(!(function_exists('apc_store') && ini_get('apc.enabled')) || version_compare(phpversion('apc'), '3.0.17', '>='))) {
    $majorProblems[] = 'Upgrade your <strong>APC</strong> extension (3.0.17+)';
}

if (!function_exists('token_get_all')) {
    $minorProblems[] = 'Install and enable the <strong>Tokenizer</strong> extension.';
}

if (!function_exists('mb_strlen')) {
    $minorProblems[] = 'Install and enable the <strong>mbstring</strong> extension.';
}

if (!function_exists('iconv')) {
    $minorProblems[] = 'Install and enable the <strong>iconv</strong> extension.';
}

if (!function_exists('utf8_decode')) {
    $minorProblems[] = 'Install and enable the <strong>XML</strong> extension.';
}

if (PHP_OS != 'WINNT' && !function_exists('posix_isatty')) {
    $minorProblems[] = 'Install and enable the <strong>php_posix</strong> extension (used to colorize the CLI output).';
}

if (!class_exists('Locale')) {
    $minorProblems[] = 'Install and enable the <strong>intl</strong> extension.';
} else {
    $version = '';

    if (defined('INTL_ICU_VERSION')) {
        $version =  INTL_ICU_VERSION;
    } else {
        $reflector = new \ReflectionExtension('intl');

        ob_start();
        $reflector->info();
        $output = strip_tags(ob_get_clean());

        preg_match('/^ICU version (.*)$/m', $output, $matches);
        $version = $matches[1];
    }

    if (!version_compare($version, '4.0', '>=')) {
        $minorProblems[] = 'Upgrade your <strong>intl</strong> extension with a newer ICU version (4+).';
    }
}

if (!class_exists('SQLite3') && !in_array('sqlite', PDO::getAvailableDrivers())) {
    $majorProblems[] = 'Install and enable the <strong>SQLite3</strong> or <strong>PDO_SQLite</strong> extension.';
}

if (!function_exists('json_encode')) {
    $majorProblems[] = 'Install and enable the <strong>json</strong> extension.';
}

if (!function_exists('session_start')) {
    $majorProblems[] = 'Install and enable the <strong>session</strong> extension.';
}

if (!function_exists('ctype_alpha')) {
    $majorProblems[] = 'Install and enable the <strong>ctype</strong> extension.';
}

if (!function_exists('token_get_all')) {
    $majorProblems[] = 'Install and enable the <strong>Tokenizer</strong> extension.';
}

// php.ini
if (!ini_get('date.timezone')) {
    $phpini = true;
    $majorProblems[] = 'Set the "<strong>date.timezone</strong>" setting in php.ini<a href="#phpini">*</a> (like Europe/Paris).';
}

if (ini_get('short_open_tag')) {
    $phpini = true;
    $minorProblems[] = 'Set <strong>short_open_tag</strong> to <strong>off</strong> in php.ini<a href="#phpini">*</a>.';
}

if (ini_get('magic_quotes_gpc')) {
    $phpini = true;
    $minorProblems[] = 'Set <strong>magic_quotes_gpc</strong> to <strong>off</strong> in php.ini<a href="#phpini">*</a>.';
}

if (ini_get('register_globals')) {
    $phpini = true;
    $minorProblems[] = 'Set <strong>register_globals</strong> to <strong>off</strong> in php.ini<a href="#phpini">*</a>.';
}

if (ini_get('session.auto_start')) {
    $phpini = true;
    $minorProblems[] = 'Set <strong>session.auto_start</strong> to <strong>off</strong> in php.ini<a href="#phpini">*</a>.';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link href="bundles/sensiodistribution/webconfigurator/css/install.css" rel="stylesheet" media="all" />
        <title>Symfony Configuration</title>
    </head>
    <body>
        <div id="symfony-wrapper">
            <div id="symfony-content">
                <div class="symfony-blocks-install">
                <div class="symfony-block-logo">
                    <img src="bundles/sensiodistribution/webconfigurator/images/logo-big.gif" alt="sf_symfony" />
                </div>

                <div class="symfony-block-content">
                    <h1>Welcome!</h1>
                    <p>Welcome to your new Symfony project.</p>
                    <p>This script will guide you through the basic configuration of your project. You can also do the same by editing the ‘<strong>app/config/parameters.ini</strong>’ file directly.</p>

                    <?php if (count($majorProblems)): ?>
                        <h2>
                            <span><?php echo count($majorProblems) ?> Major problems</span>
                        </h2>
                        <p>Major problems have been detected and <strong>must</strong> be fixed before continuing :</p>
                        <ol>
                            <?php foreach ($majorProblems as $problem): ?>
                                <li><?php echo $problem; ?></li>
                            <?php endforeach ?>
                        </ol>
                    <?php endif ?>

                    <?php if (count($minorProblems)): ?>
                        <h2>Recommendations</h2>
                        <p>
                            <?php if ($majorProblems): ?>
                                Additionally, to
                            <?php else: ?>
                                To<?php endif; ?>
                            enhance your Symfony experience, it’s recommended that you fix the following :
                        </p>
                        <ol>
                            <?php foreach ($minorProblems as $problem): ?>
                            <li><?php echo $problem; ?></li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif ?>

                    <?php if ($phpini): ?>
                            <a id="phpini"></a>
                            <p>*
                                <?php if (get_cfg_var('cfg_file_path')): ?>
                                    Changes to the <strong>php.ini</strong> file must be done in "<strong><?php echo get_cfg_var('cfg_file_path') ?></strong>".
                                <?php else: ?>
                                    To change settings, create a "<strong>php.ini</strong>".
                                <?php endif; ?>
                            </p>
                    <?php endif; ?>

                    <ul class="symfony-install-continue">
                        <?php if (!count($majorProblems)): ?>
                            <li><a href="app_dev.php/_configurator/">Configure your Symfony Application online</a></li>
                            <li><a href="app_dev.php/">Bypass configuration and go to the Welcome page</a></li>
                        <?php endif ?>
                        <li><a href="config.php">Re-check configuration</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="version">Symfony Standard Edition</div>
    </body>
</html>
