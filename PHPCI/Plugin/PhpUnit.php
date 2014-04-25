<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;

use PHPCI;
use PHPCI\Builder;
use PHPCI\Model\Build;

/**
* PHP Unit Plugin - Allows PHP Unit testing.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpUnit implements PHPCI\Plugin, PHPCI\ZeroConfigPlugin
{
    protected $args;
    protected $phpci;

    /**
     * @var string|string[] $directory The directory (or array of dirs) to run PHPUnit on
     */
    protected $directory;

    /**
     * @var string $runFrom When running PHPUnit with an XML config, the command is run from this directory
     */
    protected $runFrom;

    /**
     * @var string, in cases where tests files are in a sub path of the /tests path,
     * allows this path to be set in the config.
     */
    protected $path;

    protected $coverage = "";

    /**
     * @var string|string[] $xmlConfigFile The path (or array of paths) of an xml config for PHPUnit
     */
    protected $xmlConfigFile;

    public static function canExecute($stage, Builder $builder, Build $build)
    {
        if ($stage == 'test' && !is_null(self::findConfigFile($builder->buildPath))) {
            return true;
        }

        return false;
    }

    public static function findConfigFile($buildPath)
    {
        if (file_exists($buildPath . '/phpunit.xml')) {
            return $buildPath . '/phpunit.xml';
        }

        if (file_exists($buildPath . '/tests/phpunit.xml')) {
            return $buildPath . '/tests/phpunit.xml';
        }

        if (file_exists($buildPath . '/phpunit.xml.dist')) {
            return $buildPath . '/phpunit.xml.dist';
        }

        if (file_exists($buildPath . '/tests/phpunit.xml.dist')) {
            return $buildPath . '/tests/phpunit.xml.dist';
        }

        return null;
    }

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;

        if (!count($options)) {
            $this->runFrom = $phpci->buildPath;
            $this->xmlConfigFile = self::findConfigFile($phpci->buildPath);
        }

        if (isset($options['directory'])) {
            $this->directory = $options['directory'];
        }

        if (isset($options['config'])) {
            $this->xmlConfigFile = $options['config'];
        }

        if (isset($options['run_from'])) {
            $this->runFrom = $options['run_from'];
        }

        if (isset($options['args'])) {
            $this->args = $options['args'];
        }

        if (isset($options['path'])) {
            $this->path = $options['path'];
        }

        if (isset($options['coverage'])) {
            $this->coverage = " --coverage-html {$options['coverage']} ";
        }
    }

    /**
    * Runs PHP Unit tests in a specified directory, optionally using specified config file(s).
    */
    public function execute()
    {
        $success = true;

        // Run any config files first. This can be either a single value or an array.
        if ($this->xmlConfigFile !== null) {
            $success &= $this->runConfigFile($this->xmlConfigFile);
        }

        // Run any dirs next. Again this can be either a single value or an array.
        if ($this->directory !== null) {
            $success &= $this->runDir($this->directory);
        }

        return $success;
    }

    protected function runConfigFile($configPath)
    {
        if (is_array($configPath)) {
            return $this->recurseArg($configPath, array($this, "runConfigFile"));
        } else {
            if ($this->runFrom) {
                $curdir = getcwd();
                chdir($this->phpci->buildPath.'/'.$this->runFrom);
            }


            $phpunit = $this->phpci->findBinary('phpunit');

            if (!$phpunit) {
                $this->phpci->logFailure('Could not find phpunit.');
                return false;
            }


            $cmd = $phpunit . ' %s -c "%s" ' . $this->coverage . $this->path;
            $success = $this->phpci->executeCommand($cmd, $this->args, $this->phpci->buildPath . $configPath);

            if ($this->runFrom) {
                chdir($curdir);
            }

            return $success;
        }
    }

    protected function runDir($dirPath)
    {
        if (is_array($dirPath)) {
            return $this->recurseArg($dirPath, array($this, "runDir"));
        } else {
            $curdir = getcwd();
            chdir($this->phpci->buildPath);

            $phpunit = $this->phpci->findBinary('phpunit');

            if (!$phpunit) {
                $this->phpci->logFailure('Could not find phpunit.');
                return false;
            }

            $cmd = $phpunit . ' %s "%s"';
            $success = $this->phpci->executeCommand($cmd, $this->args, $this->phpci->buildPath . $dirPath);
            chdir($curdir);
            return $success;
        }
    }

    protected function recurseArg($array, $callable)
    {
        $success = true;
        foreach ($array as $subItem) {
            $success &= call_user_func($callable, $subItem);
        }
        return $success;
    }
}
