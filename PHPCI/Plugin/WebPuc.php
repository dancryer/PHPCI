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
 * Web Package Update Checker validates web projects to ensure they use the latest available versions of web packages.
 * @author       Ilia Grabko <ilya4041@yandex.ru>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class WebPuc implements PHPCI\Plugin
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
    protected $exclude;

    /**
     * @var string
     */
    protected $allowSupported;

    /**
     * @var string
     */
    protected $update;

    /**
     * Standard Constructor
     *
     * $options['directory'] Output Directory. Default: %BUILDPATH%
     * $options['filename']  Phar Filename. Default: build.phar
     * $options['regexp']    Regular Expression Filename Capture. Default: /\.php$/
     * $options['stub']      Stub Content. No Default Value
     *
     * @param Builder $phpci
     * @param Build   $build
     * @param array   $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci    = $phpci;
        $this->build    = $build;
        $this->directory = $phpci->buildPath;

        $this->exclude = "";
        $this->allowSupported = "";
        $this->update = "";

        if (isset($options['exclude'])) {
            $this->exclude = $options['exclude'];
        }
        if (isset($options['allow-supported'])) {
            $this->allowSupported = $options['allow-supported'];
        }
        if (isset($options['update'])) {
            $this->update = $options['update'];
        }
    }

    /**
     * Runs Web-Puc.
     */
    public function execute()
    {
        $path = $this->phpci->buildPath;
        $webpuc = $this->phpci->findBinary('web-puc');
        $cmd = $webpuc . ' %s %s %s %s';

        $exclude = '';
        if (!empty($this->exclude)) {
            $exclude = ' -e "' . $this->exclude . '"';
        }

        $this->phpci->executeCommand(
            $cmd,
            $exclude,
            $this->allowSupported,
            $this->update,
            $path
        );
        $output = $this->phpci->getLastOutput();

        $failed_findings = 0;
        $stat = json_decode($output);
        if ($stat === null) {
            $this->phpci->log($output);
            throw new \Exception('Could not parse web-puc output.');
        }
        if(isset($stat->findings))
            foreach ($stat->findings as $finding)
                if($finding->failure){
                    $failed_findings++;

                    $this->build->reportError(
                        $this->phpci,
                        'web_puc',
                        $finding->description,
                        PHPCI\Model\BuildError::SEVERITY_HIGH,
                        $finding->location->path,
                        $finding->location->beginLine,
                        $finding->location->endLine
                    );
                }

        $this->phpci->logExecOutput(true);

        return $failed_findings == 0;
    }
}
