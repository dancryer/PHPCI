<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

/**
* PHP Code Sniffer Plugin - Allows PHP Code Sniffer testing.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpCodeSniffer implements \PHPCI\Plugin
{
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var array
     */
    protected $suffixes;

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string
     */
    protected $standard;

    /**
     * @var string
     */
    protected $tab_width;

    /**
     * @var string
     */
    protected $encoding;

    /**
     * @var string, based on the assumption the root may not hold the code to be
     * tested, exteds the base path
     */
    protected $path;

    /**
     * @var array - paths to ignore
     */
    protected $ignore;

    /**
     * @param \PHPCI\Builder $phpci
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci        = $phpci;
        $this->build        = $build;
        $this->suffixes     = array('php');
        $this->directory    = $phpci->buildPath;
        $this->standard     = 'PSR2';
        $this->tab_width    = '';
        $this->encoding     = '';
        $this->path         = '';
        $this->ignore       = $this->phpci->ignore;

        if (isset($options['suffixes'])) {
            $this->suffixes = (array)$options['suffixes'];
        }

        if (isset($options['directory'])) {
            $this->directory = $options['directory'];
        }

        if (isset($options['standard'])) {
            $this->standard = $options['standard'];
        }

        if (!empty($options['tab_width'])) {
            $this->tab_width = ' --tab-width='.$options['tab_width'];
        }

        if (!empty($options['encoding'])) {
            $this->encoding = ' --encoding=' . $options['encoding'];
        }

        if (isset($options['path'])) {
            $this->path = $options['path'];
        }

        if (isset($options['ignore'])) {
            $this->ignore = $options['ignore'];
        }
    }

    /**
    * Runs PHP Code Sniffer in a specified directory, to a specified standard.
    */
    public function execute()
    {
        list($ignore, $standard, $suffixes) = $this->getFlags();

        $phpcs = $this->phpci->findBinary('phpcs');

        if (!$phpcs) {
            $this->phpci->logFailure('Could not find phpcs.');
            return false;
        }

        $cmd = $phpcs . ' --report=emacs %s %s %s %s %s "%s"';
        $success = $this->phpci->executeCommand(
            $cmd,
            $standard,
            $suffixes,
            $ignore,
            $this->tab_width,
            $this->encoding,
            $this->phpci->buildPath . $this->path
        );

        $output = $this->phpci->getLastOutput();

        $matches = array();
        if (preg_match_all('/\: warning \-/', $output, $matches)) {
            $this->build->storeMeta('phpcs-warnings', count($matches[0]));
        }

        $matches = array();
        if (preg_match_all('/\: error \-/', $output, $matches)) {
            $this->build->storeMeta('phpcs-errors', count($matches[0]));
        }

        return $success;
    }

    protected function getFlags()
    {
        $ignore = '';
        if (count($this->ignore)) {
            $ignore = ' --ignore=' . implode(',', $this->ignore);
        }

        if (strpos($this->standard, '/') !== false) {
            $standard = ' --standard='.$this->directory.$this->standard;
        } else {
            $standard = ' --standard='.$this->standard;
        }

        $suffixes = '';
        if (count($this->suffixes)) {
            $suffixes = ' --extensions=' . implode(',', $this->suffixes);
        }

        return array($ignore, $standard, $suffixes);
    }
}
