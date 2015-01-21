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
* Gulp Plugin - Provides access to gulp functionality.
* @author       Dirk Heilig <dirk@heilig-online.com>
* @package      PHPCI
* @subpackage   Plugins
*/
class Gulp implements \PHPCI\Plugin
{
    protected $directory;
    protected $task;
    protected $preferDist;
    protected $phpci;
    protected $build;
    protected $gulp;
    protected $gulpfile;

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
        $path = $phpci->buildPath;
        $this->build = $build;
        $this->phpci = $phpci;
        $this->directory = $path;
        $this->task = null;
        $this->gulp = $this->phpci->findBinary('gulp');
        $this->gulpfile = 'gulpfile.js';

        // Handle options:
        if (isset($options['directory'])) {
            $this->directory = $path . '/' . $options['directory'];
        }

        if (isset($options['task'])) {
            $this->task = $options['task'];
        }

        if (isset($options['gulp'])) {
            $this->gulp = $options['gulp'];
        }

        if (isset($options['gulpfile'])) {
            $this->gulpfile = $options['gulpfile'];
        }
    }

    /**
    * Executes gulp and runs a specified command (e.g. install / update)
    */
    public function execute()
    {
        // if npm does not work, we cannot use gulp, so we return false
        $cmd = 'cd %s && npm install';
        if (IS_WIN) {
            $cmd = 'cd /d %s && npm install';
        }
        if (!$this->phpci->executeCommand($cmd, $this->directory)) {
            return false;
        }

        // build the gulp command
        $cmd = 'cd %s && ' . $this->gulp;
        if (IS_WIN) {
            $cmd = 'cd /d %s && ' . $this->gulp;
        }
        $cmd .= ' --no-color';
        $cmd .= ' --gulpfile %s';
        $cmd .= ' %s'; // the task that will be executed

        // and execute it
        return $this->phpci->executeCommand($cmd, $this->directory, $this->gulpfile, $this->task);
    }
}
