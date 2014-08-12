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
* PhpMetrics  Plugin - Allows PHPMetrics integration.
* @author       Jean-François Lépine <lepinejeanfrancois@yahoo.fr>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpMetrics implements PHPCI\Plugin, PHPCI\ZeroConfigPlugin
{
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var \PHPCI\Model\Build
     */
    protected $build;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $extensions;

    /**
     * @var string
     */
    protected $failure;

    /**
     * @var array
     */
    protected $exclude;

    /**
     * @var string
     */
    protected $configFile;

    /**
     * @inheritdoc
     */
    public static function canExecute($stage, Builder $builder, Build $build)
    {
        if ($stage == 'test') {
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;

        $this->path = rtrim($this->phpci->buildPath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

        if (isset($options['zero_config']) && $options['zero_config']) {
            $this->allowed_warnings = -1;
        }

        if (!empty($options['path'])) {
            $this->path = $this->path.$options['path'];
        }

        if (array_key_exists('allowed_warnings', $options)) {
            $this->allowed_warnings = (int)$options['allowed_warnings'];
        }

        foreach (array('exclude', 'extensions', 'failure') as $key) {
            $this->overrideSetting($options, $key);
        }

        if (empty($options['config']) && empty($options['directory'])) {
            $this->configFile = file_exists($this->phpci->buildPath . '.phpmetrics.yml')
                    ? $this->phpci->buildPath . '.phpmetrics.yml'
                    : null;
        }

        $this->location = $this->phpci->buildPath . '..' . DIRECTORY_SEPARATOR . 'phpmetrics';
        if(!file_exists($this->location)) {
            mkdir($this->location, 0777, true);
        }
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $bin = $this->phpci->findBinary('phpmetrics');
        if (!$bin) {
            $this->phpci->logFailure('Could not find PhpMetrics.');
            return false;
        }

        if (!is_writable($this->location)) {
            throw new \Exception(sprintf('The location %s is not writable.', $this->location));
        }

        $success = $this->executePhpMetrics($bin);

        if(!$success) {
            $this->phpci->log($this->phpci->getLastOutput());
            throw new \Exception('Could not run PhpMetrics.');
        }

        $data = $this->processReport(trim($this->phpci->getLastOutput()));
        $this->build->storeMeta('phpmetrics', $data);


        return $success;
    }

    /**
     * Override setting
     *
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
     * Processes report
     *
     * @param $xmlString
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    protected function processReport($xmlString)
    {
        $project = simplexml_load_string($xmlString);

        if ($project === false) {
            $this->phpci->log($xmlString);
            throw new \Exception('Could not process PhpMetrics report XML.');
        }

        $array = array();
        foreach($project->attributes() as $key => $value) {
            $array[$key] = (string) $value;
        }
        return $array;
    }


    /**
     * Execute PhpMetrics
     *
     * @param $bin
     * @return bool
     */
    protected function executePhpMetrics($bin)
    {

        if(count($this->exclude, COUNT_NORMAL)) {
            $args[] = sprintf('--excluded-dirs=%s', implode('|', $this->exclude));
        }

        if(count($this->extensions, COUNT_NORMAL)) {
            $args[] = sprintf('--extensions=%s', implode('|', $this->extensions));
        }

        if(!is_null($this->failure)) {
            $args[] = sprintf('--failure-condition=%s', $this->failure);
        }

        if(!is_null($this->configFile)) {
            $args[] = sprintf('--config=%s', $this->configFile);
        }

        $args[] = '--report-xml=php://stdout';
        $args[] = sprintf('--chart-bubbles="%s"', $this->location.DIRECTORY_SEPARATOR.'chart-bubbles.svg');
        $args[] = sprintf('--report-html="%s"', $this->location.DIRECTORY_SEPARATOR.'report.html');
        $args[] = '--quiet';

        // Disable exec output logging, as we don't want the XML report in the log:
        $this->phpci->logExecOutput(false);

        // Run PhpMetrics:
        $cmd = sprintf('%1$s %2$s %3$s', $bin, implode(' ', $args), $this->path);
        $success = $this->phpci->executeCommand($cmd);

        // Re-enable exec output logging:
        $this->phpci->logExecOutput(true);

        return $success;
    }
}
