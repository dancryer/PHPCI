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

/**
 * Ftp deploy
 * @author       Grzegorz Wójcik <grzechowojcik@gmail.com>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class FtpDeploy implements \PHPCI\Plugin
{
    protected $phpci;
    protected $build;
    protected $configFile = 'deployment.ini';

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
        if(isset($options['configFile'])) {
            $this->configFile = $options['configFile'];
        }
        $this->executable = $this->phpci->findBinary('deployment');
    }

    /**
     * Runs Ftp Upload.
     */
    public function execute()
    {
        $curdir = getcwd();
        chdir($this->phpci->buildPath);

        $deploy = $this->executable;

        if(!$this->build->isSuccessful()) {
            return false;
        }
        
        if (!$deploy) {
            $this->phpci->logFailure(Lang::get('could_not_find', 'Ftp Deployment'));
            return false;
        }

        $success = $this->phpci->executeCommand($deploy.' '.$this->configFile);
        chdir($curdir);

        return $success;
    }
}
