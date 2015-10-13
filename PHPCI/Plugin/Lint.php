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
 * PHP Lint Plugin - Provides access to PHP lint functionality.
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Lint implements PHPCI\Plugin
{
    protected $directories;
    protected $recursive = true;
    protected $ignore;
    protected $phpci;
    protected $build;
    protected $failedPaths = array();

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
        $this->phpci        = $phpci;
        $this->build = $build;
        $this->directories    = array('');
        $this->ignore = $phpci->ignore;

        if (!empty($options['directory'])) {
            $this->directories[] = $options['directory'];
        }

        if (!empty($options['directories'])) {
            $this->directories = $options['directories'];
        }

        if (array_key_exists('recursive', $options)) {
            $this->recursive = $options['recursive'];
        }
    }

    /**
     * Executes parallel lint
     */
    public function execute()
    {
        $this->phpci->quiet = true;
        $success = true;

        $php = $this->phpci->findBinary('php');

        $this->phpci->logExecOutput(false);

        foreach ($this->directories as $dir) {
            if (!$this->lintDirectory($php, $dir)) {
                $success = false;
            }
        }

        $this->phpci->quiet = false;

        $this->phpci->logExecOutput(true);

        $this->build->storeMeta('phplint-warnings', count($this->failedPaths));
        $this->build->storeMeta('phplint-data', $this->failedPaths);

        return $success;
    }

    /**
     * Lint an item (file or directory) by calling the appropriate method.
     * @param $php
     * @param $item
     * @param $itemPath
     * @return bool
     */
    protected function lintItem($php, $item, $itemPath)
    {
        $success = true;

        if ($item->isFile() && $item->getExtension() == 'php' && !$this->lintFile($php, $itemPath)) {
            $success = false;
        } elseif ($item->isDir() && $this->recursive && !$this->lintDirectory($php, $itemPath . '/')) {
            $success = false;
        }

        return $success;
    }

    /**
     * Run php -l against a directory of files.
     * @param $php
     * @param $path
     * @return bool
     */
    protected function lintDirectory($php, $path)
    {
        $success = true;
        $directory = new \DirectoryIterator($this->phpci->buildPath . $path);

        foreach ($directory as $item) {
            if ($item->isDot()) {
                continue;
            }

            $itemPath = $path . $item->getFilename();

            if (in_array($itemPath, $this->ignore)) {
                continue;
            }

            if (!$this->lintItem($php, $item, $itemPath)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Run php -l against a specific file.
     * @param $php
     * @param $path
     * @return bool
     */
    protected function lintFile($php, $path)
    {
        $success = true;

        if (!$this->phpci->executeCommand($php . ' -l "%s" 2>&1', $this->phpci->buildPath . $path)) {
            $output = $this->phpci->getLastOutput();
            preg_match('/Parse error:\s*syntax error,(.+?)\s+in\s+.+?\s*line\s+(\d+)/', $output, $matches);

            $this->failedPaths[] = array(
                'file' => $path,
                'line' => trim($matches[2]),
                'message' => trim($matches[1])
            );

            $success = false;
        }

        return $success;
    }
}
