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
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;
use PHPCI\Plugin\Util\TestResultParsers\Codeception as Parser;
use Psr\Log\LogLevel;

/**
 * Codeception Plugin - Enables full acceptance, unit, and functional testing.
 * @author       Don Gilbert <don@dongilbert.net>
 * @author       Igor Timoshenko <contact@igortimoshenko.com>
 * @author       Adam Cooper <adam@networkpie.co.uk>
 * @package      PHPCI
 * @subpackage   Plugins
 */

class Codeception extends AbstractExecutingPlugin implements PHPCI\ZeroConfigPlugin
{
    /** @var string */
    protected $args = '';

    /**
     * @var string $ymlConfigFile The path of a yml config for Codeception
     */
    protected $ymlConfigFile;

    /**
     * @var string $path The path to the codeception tests folder.
     */
    protected $path;

    /**
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
     * Try and find the codeception YML config file.
     * @param $buildPath
     * @return null|string
     */
    public static function findConfigFile($buildPath)
    {
        if (file_exists($buildPath . 'codeception.yml')) {
            return 'codeception.yml';
        }

        if (file_exists($buildPath . 'codeception.dist.yml')) {
            return 'codeception.dist.yml';
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
        $this->path = 'tests/';

        if (empty($options['config'])) {
            $this->ymlConfigFile = self::findConfigFile($this->phpci->buildPath);
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
        if (empty($this->ymlConfigFile)) {
            throw new \Exception("No configuration file found");
        }

        // Run any config files first. This can be either a single value or an array.
        return $this->runConfigFile($this->ymlConfigFile);
    }

    /**
     * Run tests from a Codeception config file.
     * @param $configPath
     * @return bool|mixed
     * @throws \Exception
     */
    protected function runConfigFile($configPath)
    {
        if (is_array($configPath)) {
            return $this->recurseArg($configPath, array($this, 'runConfigFile'));
        } else {
            $this->executor->setQuiet(true);

            $codecept = $this->executor->findBinary('codecept');

            $cmd = 'cd "%s" && ' . $codecept . ' run -c "%s" --tap ' . $this->args;

            if (IS_WIN) {
                $cmd = 'cd /d "%s" && ' . $codecept . ' run -c "%s" --xml ' . $this->args;
            }

            $configPath = $this->buildPath . $configPath;
            $success = $this->executor->executeCommand($cmd, $this->buildPath, $configPath);

            $this->logger->log(
                'Codeception XML path: '. $this->buildPath . $this->path . '_output/report.xml',
                Loglevel::DEBUG
            );
            $xml = file_get_contents($this->buildPath . $this->path . '_output/report.xml', false);

            $parser = new Parser($this->phpci, $xml);
            $output = $parser->parse();

            $meta = array(
                'tests' => $parser->getTotalTests(),
                'timetaken' => $parser->getTotalTimeTaken(),
                'failures' => $parser->getTotalFailures()
            );

            $this->build->storeMeta('codeception-meta', $meta);
            $this->build->storeMeta('codeception-data', $output);
            $this->build->storeMeta('codeception-errors', $parser->getTotalFailures());

            $this->executor->setQuiet(false);

            return $success;
        }
    }
}
