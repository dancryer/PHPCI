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
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;
use PHPCI\Plugin\Util\TapParser;

/**
 * Codeception Plugin - Enables full acceptance, unit, and functional testing
 *
 * @author       Don Gilbert <don@dongilbert.net>
 * @author       Igor Timoshenko <contact@igortimoshenko.com>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Codeception implements PHPCI\Plugin, PHPCI\ZeroConfigPlugin
{
    /**
     * @var string
     */
    protected $args = '';

    /**
     * @var Build
     */
    protected $build;

    /**
     * @var Builder
     */
    protected $phpci;

    /**
     * @var string|string[] The path (or array of paths) of an yml config for Codeception
     */
    protected $configFile;

    /**
     * @var string The path where the reports and logs are stored
     */
    protected $logPath = 'tests/_output';

    /**
     * @var string $path The path to the codeception tests folder.
     */
    protected $path;

    /**
     * Set up the plugin, configure options, etc.
     *
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;

        if (isset($options['config'])) {
            $this->configFile = $options['config'];
        }

        if (isset($options['args'])) {
            $this->args = (string) $options['args'];
        }

        if (isset($options['path'])) {
            $this->path = $options['path'];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $success = true;

        $this->phpci->logExecOutput(false);

        // Run any config files first. This can be either a single value or an array
        if ($this->configFile !== null) {
            $success &= $this->runConfigFile($this->configFile);
        }

        $tapString = file_get_contents(
            $this->phpci->buildPath . $this->logPath . DIRECTORY_SEPARATOR . 'report.tap.log'
        );

        try {
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

    /**
     * {@inheritDoc}
     */
    public static function canExecute($stage, Builder $builder, Build $build)
    {
        return $stage === 'test';
    }

    /**
     * Run tests from a Codeception config file
     *
     * @param string $configPath
     * @return bool|mixed
     */
    protected function runConfigFile($configPath)
    {
        if (is_array($configPath)) {
            return $this->recurseArg($configPath, array($this, 'runConfigFile'));
        } else {

            $this->phpci->logExecOutput(false);

            $codecept = $this->phpci->findBinary('codecept');

            $cmd = 'cd "%s" && ' . $codecept . ' run -c "%s" --tap ' . $this->args;

            if (IS_WIN) {
                $cmd = 'cd /d "%s" && ' . $codecept . ' run -c "%s" --tap ' . $this->args;
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

    /**
     * @param array $array
     * @param \Callback $callable
     * @return bool|mixed
     */
    protected function recurseArg(array $array, $callable)
    {
        $success = true;

        foreach ($array as $subItem) {
            $success &= call_user_func($callable, $subItem);
        }

        return $success;
    }
}
