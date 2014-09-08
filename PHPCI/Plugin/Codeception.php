<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

/**
 * Codeception Plugin - Enables full acceptance, unit, and functional testing.
 * @author       Don Gilbert <don@dongilbert.net>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Codeception implements \PHPCI\Plugin
{
    /**
     * @var string
     */
    protected $args = '';

    /**
     * @var Builder
     */
    protected $phpci;

    protected $build;

    /**
     * @var string|string[] $xmlConfigFile The path (or array of paths) of an xml config for PHPUnit
     */
    protected $xmlConfigFile;

    /**
     * @var string $path The path to the codeception tests folder.
     */
    protected $path;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;

        if (isset($options['config'])) {
            $this->xmlConfigFile = $options['config'];
        }
        if (isset($options['args'])) {
            $this->args = (string) $options['args'];
        }
        if (isset($options['path'])) {
            $this->path = $options['path'];
        }
    }

    /**
     * Runs Codeception tests, optionally using specified config file(s).
     */
    public function execute()
    {
        $success = true;

        // Run any config files first. This can be either a single value or an array.
        if ($this->xmlConfigFile !== null) {
            $success &= $this->runConfigFile($this->xmlConfigFile);
        }

        return $success;
    }

    protected function runConfigFile($configPath)
    {
        if (is_array($configPath)) {
            return $this->recurseArg($configPath, array($this, "runConfigFile"));
        } else {

            $this->phpci->logExecOutput(false);

            $codecept = $this->phpci->findBinary('codecept');

            if (!$codecept) {
                $this->phpci->logFailure('Could not find codeception.');
                return false;
            }

            $cmd = 'cd "%s" && ' . $codecept . ' run -c "%s" --tap '. $this->args;
            if (IS_WIN) {
                $cmd = 'cd /d "%s" && ' . $codecept . ' run -c "%s" --tap '. $this->args;
            }

            $configPath = $this->phpci->buildPath . $configPath;
            $success = $this->phpci->executeCommand($cmd, $this->phpci->buildPath, $configPath);

            try {
                $tapString = file_get_content($this->phpci->buildPath . '/' . $this->path . '/_output/report.tap.log', false);
                $tapParser = new TapParser($tapString);
                $output = $tapParser->parse();
            } catch (\Exception $ex) {
                $this->phpci->logFailure($tapString);
                throw $ex;
            }

            $failures = $tapParser->getTotalFailures();

            $this->build->storeMeta('codeception-errors', $failures);
            $this->build->storeMeta('codeception-data', $output);

            $this->phpci->logExecOutput(true);

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
