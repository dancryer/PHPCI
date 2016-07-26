<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */
namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

/**
 * Php Parallel Lint Plugin - Provides access to PHP lint functionality.
 *
 * @author       Vaclav Makes <vaclav@makes.cz>
 */
class PhpParallelLint implements \PHPCI\Plugin
{
    /**
     * @type \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @type \PHPCI\Model\Build
     */
    protected $build;

    /**
     * @type string
     */
    protected $directory;

    /**
     * @type array - paths to ignore
     */
    protected $ignore;

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
    public function __construct(Builder $phpci, Build $build, array $options = [])
    {
        $this->phpci     = $phpci;
        $this->build     = $build;
        $this->directory = $phpci->buildPath;
        $this->ignore    = $this->phpci->ignore;

        if (isset($options['directory'])) {
            $this->directory = $phpci->buildPath . $options['directory'];
        }

        if (isset($options['ignore'])) {
            $this->ignore = $options['ignore'];
        }
    }

    /**
     * Executes parallel lint
     */
    public function execute()
    {
        list($ignore) = $this->getFlags();

        $phplint = $this->phpci->findBinary('parallel-lint');

        $cmd     = $phplint . ' %s "%s"';
        $success = $this->phpci->executeCommand(
            $cmd,
            $ignore,
            $this->directory
        );

        $output = $this->phpci->getLastOutput();

        $matches = [];
        if (preg_match_all('/Parse error\:/', $output, $matches)) {
            $this->build->storeMeta('phplint-errors', count($matches[0]));
        }

        return $success;
    }

    /**
     * Produce an argument string for PHP Parallel Lint.
     *
     * @return array
     */
    protected function getFlags()
    {
        $ignoreFlags = [];
        foreach ($this->ignore as $ignoreDir) {
            $ignoreFlags[] = '--exclude "' . $this->phpci->buildPath . $ignoreDir . '"';
        }
        $ignore = implode(' ', $ignoreFlags);

        return [$ignore];
    }
}
