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

/**
* PHP Mess Detector Plugin - Allows PHP Mess Detector testing.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpMessDetector extends AbstractExecutingPlugin implements PHPCI\ZeroConfigPlugin
{
    /**
     * @var array
     */
    protected $suffixes;

    /**
     * @var string, based on the assumption the root may not hold the code to be
     * tested, exteds the base path only if the provided path is relative. Absolute
     * paths are used verbatim
     */
    protected $path;

    /**
     * @var array - paths to ignore
     */
    protected $ignore;

    /**
     * Array of PHPMD rules. Can be one of the builtins (codesize, unusedcode, naming, design, controversial)
     * or a filenname (detected by checking for a / in it), either absolute or relative to the project root.
     * @var array
     */
    protected $rules;

    /**
     * Check if this plugin can be executed.
     * @param $stage
     * @param Builder $builder
     * @param Build $build
     * @return bool
     */
    public static function canExecute($stage, Builder $builder, Build $build)
    {
        if ($stage == 'test') {
            return true;
        }

        return false;
    }

    /**
     * Configure the plugin.
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $this->suffixes = array('php');
        $this->ignore = $this->phpci->ignore;
        $this->path = '';
        $this->rules = array('codesize', 'unusedcode', 'naming');
        $this->allowed_warnings = 0;

        if (isset($options['zero_config']) && $options['zero_config']) {
            $this->allowed_warnings = -1;
        }

        if (!empty($options['path'])) {
            $this->path = $options['path'];
        }

        if (array_key_exists('allowed_warnings', $options)) {
            $this->allowed_warnings = (int)$options['allowed_warnings'];
        }

        foreach (array('rules', 'ignore', 'suffixes') as $key) {
            $this->overrideSetting($options, $key);
        }
    }

    /**
     * Runs PHP Mess Detector in a specified directory.
     */
    public function execute()
    {
        if (!$this->tryAndProcessRules()) {
            return false;
        }

        $phpmdBinaryPath = $this->executor->findBinary('phpmd');

        $this->executePhpMd($phpmdBinaryPath);

        list($errorCount, $data) = $this->processReport(trim($this->executor->getLastOutput()));
        $this->build->storeMeta('phpmd-warnings', $errorCount);
        $this->build->storeMeta('phpmd-data', $data);

        return $this->wasLastExecSuccessful($errorCount);
    }

    /**
     * Override a default setting.
     * @param $options
     * @param $key
     */
    protected function overrideSetting($options, $key)
    {
        if (isset($options[$key]) && is_array($options[$key])) {
            $this->{$key} = $options[$key];
        }
    }

    /**
     * Process PHPMD's XML output report.
     * @param $xmlString
     * @return array
     * @throws \Exception
     */
    protected function processReport($xmlString)
    {
        $xml = simplexml_load_string($xmlString);

        if ($xml === false) {
            $this->logger->log($xmlString);
            throw new \Exception('Could not process PHPMD report XML.');
        }

        $warnings = 0;
        $data = array();

        foreach ($xml->file as $file) {
            $fileName = (string)$file['name'];
            $fileName = str_replace($this->buildPath, '', $fileName);

            foreach ($file->violation as $violation) {
                $warnings++;
                $warning = array(
                    'file' => $fileName,
                    'line_start' => (int)$violation['beginline'],
                    'line_end' => (int)$violation['endline'],
                    'rule' => (string)$violation['rule'],
                    'ruleset' => (string)$violation['ruleset'],
                    'priority' => (int)$violation['priority'],
                    'message' => (string)$violation,
                );

                $this->build->reportError($this->phpci, $fileName, (int)$violation['beginline'], (string)$violation);
                $data[] = $warning;
            }
        }

        return array($warnings, $data);
    }

    /**
     * Try and process the rules parameter from phpci.yml.
     * @return bool
     */
    protected function tryAndProcessRules()
    {
        if (!empty($this->rules) && !is_array($this->rules)) {
            throw new \Exception('The "rules" option must be an array.');
        }

        foreach ($this->rules as &$rule) {
            if (strpos($rule, '/') !== false) {
                $rule = $this->buildPath . $rule;
            }
        }

        return true;
    }

    /**
     * Execute PHP Mess Detector.
     * @param $binaryPath
     */
    protected function executePhpMd($binaryPath)
    {
        $cmd = $binaryPath . ' "%s" xml %s %s %s';

        $path = $this->getTargetPath();

        $ignore = '';
        if (count($this->ignore)) {
            $ignore = ' --exclude ' . implode(',', $this->ignore);
        }

        $suffixes = '';
        if (count($this->suffixes)) {
            $suffixes = ' --suffixes ' . implode(',', $this->suffixes);
        }

        // Disable exec output logging, as we don't want the XML report in the log:
        $this->executor->setQuiet(true);

        // Run PHPMD:
        $this->executor->executeCommand(
            $cmd,
            $path,
            implode(',', $this->rules),
            $ignore,
            $suffixes
        );

        // Re-enable exec output logging:
        $this->executor->setQuiet(false);
    }

    /**
     * Get the path PHPMD should be run against.
     * @return string
     */
    protected function getTargetPath()
    {
        $path = $this->buildPath . $this->path;
        if (!empty($this->path) && $this->path{0} == '/') {
            $path = $this->path;
            return $path;
        }
        return $path;
    }

    /**
     * Returns a boolean indicating if the error count can be considered a success.
     *
     * @param int $errorCount
     * @return bool
     */
    protected function wasLastExecSuccessful($errorCount)
    {
        $success = true;

        if ($this->allowed_warnings != -1 && $errorCount > $this->allowed_warnings) {
            $success = false;
            return $success;
        }
        return $success;
    }
}
