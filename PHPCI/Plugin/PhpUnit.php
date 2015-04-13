<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI;
use PHPCI\Builder;
use PHPCI\Model\Build;
use PHPCI\Plugin\Util\TapParser;
use PHPCI\Helper\BuildInterpolator;

/**
* PHP Unit Plugin - Allows PHP Unit testing.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpUnit extends AbstractInterpolatingPlugin implements PHPCI\ZeroConfigPlugin
{
    protected $args;

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

    /**
     * Check if this plugin can be executed.
     * @param $stage
     * @param Builder $builder
     * @param Build $build
     * @return bool
     */
    public static function canExecute($stage, Builder $builder, Build $build)
    {
        if ($stage == 'test' && !is_null(self::findConfigFile($builder->buildPath))) {
            return true;
        }

        return false;
    }

    /**
     * Try and find the phpunit XML config file.
     * @param $buildPath
     * @return null|string
     */
    public static function findConfigFile($buildPath)
    {
        if (file_exists($buildPath . 'phpunit.xml')) {
            return 'phpunit.xml';
        }

        if (file_exists($buildPath . 'tests/phpunit.xml')) {
            return 'tests/phpunit.xml';
        }

        if (file_exists($buildPath . 'phpunit.xml.dist')) {
            return 'phpunit.xml.dist';
        }

        if (file_exists($buildPath . 'tests/phpunit.xml.dist')) {
            return 'tests/phpunit.xml.dist';
        }

        return null;
    }

    /**
     * Configure the plugin.
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        if (empty($options['config']) && empty($options['directory'])) {
            $this->xmlConfigFile = self::findConfigFile($this->buildPath);
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
            $this->args = $this->interpolator->interpolate($options['args']);
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
        if (empty($this->xmlConfigFile) && empty($this->directory)) {
            $this->phpci->logFailure('Neither configuration file nor test directory found.');
            return false;
        }

        $success = true;

        $this->executor->setQuiet(true);

        // Run any config files first. This can be either a single value or an array.
        if ($this->xmlConfigFile !== null) {
            $success &= $this->runConfigFile($this->xmlConfigFile);
        }

        // Run any dirs next. Again this can be either a single value or an array.
        if ($this->directory !== null) {
            $success &= $this->runDir($this->directory);
        }

        $tapString = $this->executor->getLastOutput();
        $tapString = mb_convert_encoding($tapString, "UTF-8", "ISO-8859-1");

        try {
            $tapParser = new TapParser($tapString);
            $output = $tapParser->parse();
        } catch (\Exception $ex) {
            $this->logger->logFailure($tapString);
            throw $ex;
        }

        $failures = $tapParser->getTotalFailures();

        $this->build->storeMeta('phpunit-errors', $failures);
        $this->build->storeMeta('phpunit-data', $output);

        $this->executor->setQuiet(false);

        return $success;
    }

    /**
     * Run the tests defined in a PHPUnit config file.
     * @param $configPath
     * @return bool|mixed
     */
    protected function runConfigFile($configPath)
    {
        if (is_array($configPath)) {
            return $this->recurseArg($configPath, array($this, "runConfigFile"));
        } else {
            if ($this->runFrom) {
                $curdir = getcwd();
                chdir($this->buildPath.'/'.$this->runFrom);
            }

            $phpunit = $this->executor->findBinary('phpunit');

            $cmd = $phpunit . ' --tap %s -c "%s" ' . $this->coverage . $this->path;
            $success = $this->executor->executeCommand($cmd, $this->args, $this->buildPath . $configPath);

            if ($this->runFrom) {
                chdir($curdir);
            }

            return $success;
        }
    }

    /**
     * Run the PHPUnit tests in a specific directory or array of directories.
     * @param $directory
     * @return bool|mixed
     */
    protected function runDir($directory)
    {
        if (is_array($directory)) {
            return $this->recurseArg($directory, array($this, "runDir"));
        } else {
            $curdir = getcwd();
            chdir($this->buildPath);

            $phpunit = $this->executor->findBinary('phpunit');

            $cmd = $phpunit . ' --tap %s "%s"';
            $success = $this->executor->executeCommand($cmd, $this->args, $this->buildPath . $directory);
            chdir($curdir);
            return $success;
        }
    }

    /**
     * @param $array
     * @param $callable
     * @return bool|mixed
     */
    protected function recurseArg($array, $callable)
    {
        $success = true;
        foreach ($array as $subItem) {
            $success &= call_user_func($callable, $subItem);
        }
        return $success;
    }
}
