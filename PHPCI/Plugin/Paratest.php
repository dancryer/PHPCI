<?php

namespace PHPCI\Plugin;

use PHPCI;
use PHPCI\Plugin\Util\JUnitParser;

/**
 * Paratest (parallel PHP Unit) Plugin - Allows parallel PHP Unit testing.
 * @author       Brian Danchilla
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Paratest extends PhpUnit
{
    protected $log_file = "junit.log";

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

        $this->phpci->logExecOutput(false);

        // Run any config files first. This can be either a single value or an array.
        if ($this->xmlConfigFile !== null) {
            $success &= $this->runConfigFile($this->xmlConfigFile);
        }

        // Run any dirs next. Again this can be either a single value or an array.
        if ($this->directory !== null) {
            $success &= $this->runDir($this->directory);
        }

        //check output from JUnit log file
        $xml_string = file_get_contents($this->phpci->buildPath . DIRECTORY_SEPARATOR . $this->log_file);

        try {
            $junit_parser = new JUnitParser($xml_string);
            $output = $junit_parser->parse();
        } catch (\Exception $ex) {
            $this->phpci->logFailure($xml_string);
            throw $ex;
        }

        $failures = $junit_parser->getTotalFailures();

        $this->build->storeMeta('paratest-errors', $failures);
        $this->build->storeMeta('paratest-data', $output);

        $this->phpci->logExecOutput(true);

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
                chdir($this->phpci->buildPath . DIRECTORY_SEPARATOR . $this->runFrom);
            }

            $paratest = $this->phpci->findBinary('paratest');

            $cmd = $paratest . ' -c phpunit-parallel.xml -p 4 --stop-on-failure --log-junit ' . $this->log_file;
            $success = $this->phpci->executeCommand($cmd, $this->args, $this->phpci->buildPath . $configPath);

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
            chdir($this->phpci->buildPath);

            $paratest = $this->phpci->findBinary('paratest');

            $cmd = $paratest . ' -c phpunit-parallel.xml -p 4 --stop-on-failure --log-junit ' . $this->log_file;
            $success = $this->phpci->executeCommand($cmd, $this->args, $this->phpci->buildPath . $directory);
            chdir($curdir);
            return $success;
        }
    }
}
