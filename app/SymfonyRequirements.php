<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This class specifies all requirements and optional recommendations that
 * are necessary to run the Symfony Standard Edition.
 *
 * Users of PHP 5.2 should be able to run the requirements checks.
 * This is why the class must be compatible with PHP 5.2
 * (e.g. not using namespaces and closures).
 *
 * @author Tobias Schultze <http://tobion.de>
 */
class SymfonyRequirements
{
    private $requirements;

    /**
     * Constructor that initializes the requirements.
     */
    public function __construct()
    {
        $this->requirements = array();

        /* mandatory requirements follow */

        $requiredVersion = '5.3.2';
        $installedVersion = phpversion();

        $this->requirements[] = new Requirement(
            version_compare($installedVersion, $requiredVersion, '>='),
            sprintf('PHP version must be at least %s (%s installed)', $requiredVersion, $installedVersion),
            sprintf('Install PHP %s or newer (installed version is %s)', $requiredVersion, $installedVersion),
            sprintf('You are running PHP version "<strong>%s</strong>", but Symfony
                needs at least PHP "<strong>%s</strong>" to run. Before using Symfony, install
                PHP "<strong>%s</strong>" or newer.', $installedVersion, $requiredVersion, $requiredVersion)
        );

        $this->requirements[] = new Requirement(
            is_dir(__DIR__.'/../vendor/symfony'),
            'Vendor libraries must be installed',
            'Vendor libraries are missing; run "bin/vendors install" to install them',
            '<strong>CRITICAL</strong>: Vendor libraries are missing. Run "<strong>bin/vendors install</strong>" to install them.'
        );

        $this->requirements[] = new Requirement(
            is_writable(__DIR__.'/../app/cache'),
            'app/cache/ directory must be writable',
            'Change the permissions of the app/cache/ directory so that the web server can write into it',
            'Change the permissions of the "<strong>app/cache/</strong>" directory so that the web server can write into it.'
        );

        $this->requirements[] = new Requirement(
            is_writable(__DIR__.'/../app/logs'),
            'app/logs/ directory must be writable',
            'Change the permissions of the app/logs/ directory so that the web server can write into it',
            'Change the permissions of the "<strong>app/logs/</strong>" directory so that the web server can write into it.'
        );

        $this->requirements[] = new Requirement(
            ini_get('date.timezone'),
            '"date.timezone" setting must be set',
            'Set the "date.timezone" setting in php.ini (like Europe/Paris)',
            'Set the "<strong>date.timezone</strong>" setting in php.ini<a href="#phpini">*</a> (like Europe/Paris).',
            false, true
        );

        $this->requirements[] = new Requirement(
            function_exists('json_encode'),
            'json_encode() must be available',
            'Install and enable the JSON extension',
            'Install and enable the <strong>JSON</strong> extension.'
        );

        $this->requirements[] = new Requirement(
            function_exists('session_start'),
            'session_start() must be available',
            'Install and enable the session extension',
            'Install and enable the <strong>session</strong> extension.'
        );

        $this->requirements[] = new Requirement(
            function_exists('ctype_alpha'),
            'ctype_alpha() must be available',
            'Install and enable the ctype extension',
            'Install and enable the <strong>ctype</strong> extension.'
        );

        $this->requirements[] = new Requirement(
            function_exists('token_get_all'),
            'token_get_all() must be available',
            'Install and enable the Tokenizer extension',
            'Install and enable the <strong>Tokenizer</strong> extension.'
        );

        $this->requirements[] = new Requirement(
            function_exists('simplexml_import_dom'),
            'simplexml_import_dom() must be available',
            'Install and enable the SimpleXML extension',
            'Install and enable the <strong>SimpleXML</strong> extension.'
        );

        $this->requirements[] = new Requirement(
            !(function_exists('apc_store') && ini_get('apc.enabled')) || version_compare(phpversion('apc'), '3.0.17', '>='),
            'APC version must be at least 3.0.17',
            'Upgrade your APC extension (3.0.17+)',
            'Upgrade your <strong>APC</strong> extension (3.0.17+)'
        );

        /* optional recommendations follow */

        $this->requirements[] = new Requirement(
            class_exists('DomDocument'),
            'PHP-XML module should be installed',
            'Install and enable the PHP-XML module',
            'Install and enable the <strong>PHP-XML</strong> module.',
            true
        );

        $this->requirements[] = new Requirement(
            function_exists('mb_strlen'),
            'mb_strlen() should be available',
            'Install and enable the mbstring extension',
            'Install and enable the <strong>mbstring</strong> extension.',
            true
        );

        $this->requirements[] = new Requirement(
            function_exists('iconv'),
            'iconv() should be available',
            'Install and enable the iconv extension',
            'Install and enable the <strong>iconv</strong> extension.',
            true
        );

        $this->requirements[] = new Requirement(
            function_exists('utf8_decode'),
            'utf8_decode() should be available',
            'Install and enable the XML extension',
            'Install and enable the <strong>XML</strong> extension.',
            true
        );

        if (!defined('PHP_WINDOWS_VERSION_BUILD')) {
            $this->requirements[] = new Requirement(
                function_exists('posix_isatty'),
                'posix_isatty() should be available',
                'Install and enable the php_posix extension (used to colorized the CLI output)',
                'Install and enable the <strong>php_posix</strong> extension (used to colorize the CLI output).',
                true
            );
        }

        $this->requirements[] = new Requirement(
            class_exists('Locale'),
            'intl extension should be available',
            'Install and enable the intl extension (used for validators)',
            'Install and enable the <strong>intl</strong> extension (used for validators).',
            true
        );

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

            $this->requirements[] = new Requirement(
                version_compare($version, '4.0', '>='),
                'intl ICU version should be at least 4+',
                'Upgrade your intl extension with a newer ICU version (4+)',
                'Upgrade your <strong>intl</strong> extension with a newer ICU version (4+).',
                true
            );
        }

        $accelerator =
            (function_exists('apc_store') && ini_get('apc.enabled'))
            ||
            function_exists('eaccelerator_put') && ini_get('eaccelerator.enable')
            ||
            function_exists('xcache_set')
        ;

        $this->requirements[] = new Requirement(
            $accelerator,
            'a PHP accelerator should be installed',
            'Install a PHP accelerator like APC (highly recommended)',
            'Install and enable a <strong>PHP accelerator</strong> like APC (highly recommended).',
            true
        );

        $this->requirements[] = new Requirement(
            !ini_get('short_open_tag'),
            'php.ini has short_open_tag set to off',
            'Set short_open_tag to off in php.ini',
            'Set <strong>short_open_tag</strong> to <strong>off</strong> in php.ini<a href="#phpini">*</a>.',
            true, true
        );

        $this->requirements[] = new Requirement(
            !ini_get('magic_quotes_gpc'),
            'php.ini has magic_quotes_gpc set to off',
            'Set magic_quotes_gpc to off in php.ini',
            'Set <strong>magic_quotes_gpc</strong> to <strong>off</strong> in php.ini<a href="#phpini">*</a>.',
            true, true
        );

        $this->requirements[] = new Requirement(
            !ini_get('register_globals'),
            'php.ini has register_globals set to off',
            'Set register_globals to off in php.ini',
            'Set <strong>register_globals</strong> to <strong>off</strong> in php.ini<a href="#phpini">*</a>.',
            true, true
        );

        $this->requirements[] = new Requirement(
            !ini_get('session.auto_start'),
            'php.ini has session.auto_start set to off',
            'Set session.auto_start to off in php.ini',
            'Set <strong>session.auto_start</strong> to <strong>off</strong> in php.ini<a href="#phpini">*</a>.',
            true, true
        );

        $this->requirements[] = new Requirement(
            class_exists('PDO'),
            'PDO should be installed',
            'Install PDO (mandatory for Doctrine)',
            'Install <strong>PDO</strong> (mandatory for Doctrine).',
            true
        );

        if (class_exists('PDO')) {
            $drivers = PDO::getAvailableDrivers();
            $this->requirements[] = new Requirement(
                count($drivers),
                sprintf('PDO should have some drivers installed (currently available: %s)', implode(', ', $drivers) ?: 'none'),
                'Install PDO drivers (mandatory for Doctrine)',
                'Install <strong>PDO drivers</strong> (mandatory for Doctrine).',
                true
            );
        }
    }

    /**
     * Returns both requirements and recommendations.
     *
     * @return array Array of Requirement instances
     */
    public function all()
    {
        return $this->requirements;
    }

    /**
     * Returns all mandatory requirements.
     *
     * @return array Array of Requirement instances
     */
    public function getRequirements()
    {
        $array = array();
        foreach ($this->requirements as $req) {
            if (!$req->isOptional()) {
                $array[] = $req;
            }
        }

        return $array;

        /* for reference: abandoned version using closures available since PHP 5.3
        return array_filter($this->requirements, function ($req) {
            return !$req->isOptional();
        }); */
    }

    /**
     * Returns the mandatory requirements that were not met.
     *
     * @return array Array of Requirement instances
     */
    public function getFailedRequirements()
    {
        $array = array();
        foreach ($this->requirements as $req) {
            if (!$req->isFulfilled() && !$req->isOptional()) {
                $array[] = $req;
            }
        }

        return $array;
    }

    /**
     * Returns all optional recommmendations.
     *
     * @return array Array of Requirement instances
     */
    public function getRecommendations()
    {
        $array = array();
        foreach ($this->requirements as $req) {
            if ($req->isOptional()) {
                $array[] = $req;
            }
        }

        return $array;
    }

    /**
     * Returns the recommendations that were not met.
     *
     * @return array Array of Requirement instances
     */
    public function getFailedRecommendations()
    {
        $array = array();
        foreach ($this->requirements as $req) {
            if (!$req->isFulfilled() && $req->isOptional()) {
                $array[] = $req;
            }
        }

        return $array;
    }	

    /**
     * Returns whether a php.ini configuration is not correct.
     *
     * @return Boolean php.ini configuration problem?
     */
    public function hasPhpIniConfigIssue()
    {
        foreach ($this->requirements as $req) {
            if (!$req->isFulfilled() && $req->isPhpIniConfig()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the PHP configuration file (php.ini) path.
     *
     * @return string|false php.ini file path
     */
    public function getPhpIniConfigPath()
    {
        return get_cfg_var('cfg_file_path');
    }
}

/**
 * Represents a single PHP requirement, e.g. an installed extension
 * or a php.ini configuration.
 *
 * @author Tobias Schultze <http://tobion.de>
 */
class Requirement
{
    private $fulfilled;
    private $testMessage;
    private $helpText;
    private $helpHtml;
    private $optional;
    private $phpIniConfig;

    /**
     * Constructor that initializes the requirement.
     *	
     * @param Boolean $fulfilled     Whether the requirement is fulfilled
     * @param string  $testMessage   The message for testing the requirement
     * @param string  $helpText      The help text for resolving the problem
     * @param string  $helpHtml      The help text formatted in HTML (when null, it will be the same as $helpText)
     * @param Boolean $optional      Whether this is only an optional recommendation not a mandatory requirement
     * @param Boolean $phpIniConfig  Whether this requirement is part of the php.ini configuration
     */
    public function __construct($fulfilled, $testMessage, $helpText, $helpHtml = null, $optional = false, $phpIniConfig = false)
    {
        $this->fulfilled = (Boolean) $fulfilled;
        $this->testMessage = $testMessage;
        $this->helpText = $helpText;
        $this->helpHtml = $helpHtml ?: $helpText;
        $this->optional = (Boolean) $optional;
        $this->phpIniConfig = (Boolean) $phpIniConfig;
    }

    /**
     * Returns whether the requirement is fulfilled.
     *
     * @return Boolean true if fulfilled, otherwise false
     */
    public function isFulfilled()
    {
        return $this->fulfilled;
    }

    /**
     * Returns the message for testing the requirement.
     *
     * @return string The test message
     */
    public function getTestMessage()
    {
        return $this->testMessage;
    }

    /**
     * Returns the help text for resolving the problem
     *
     * @return string The help text
     */
    public function getHelpText()
    {
        return $this->helpText;
    }

    /**
     * Returns the help text formatted in HTML.
     *
     * @return string The HTML help
     */
    public function getHelpHtml()
    {
        return $this->helpHtml;
    }

    /**
     * Returns whether this is only an optional recommendation and not a mandatory requirement.
     *
     * @return Boolean true if optional, false if mandatory
     */
    public function isOptional()
    {
        return $this->optional;
    }

    /**
     * Returns whether this requirement is part of the php.ini configuration.
     *
     * @return Boolean true if part of php.ini config, otherwise false
     */
    public function isPhpIniConfig()
    {
        return $this->phpIniConfig;
    }	
}
