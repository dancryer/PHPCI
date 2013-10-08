<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;

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
    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $this->phpci        = $phpci;
        $this->suffixes     = isset($options['suffixes']) ? (array)$options['suffixes'] : array('php');
        $this->directory    = isset($options['directory']) ? $options['directory'] : $phpci->buildPath;
        $this->standard     = isset($options['standard']) ? $options['standard'] : 'PSR2';
        $this->tab_width    = isset($options['tab_width']) ? $options['tab_width'] : '';
        $this->encoding     = isset($options['encoding']) ? $options['encoding'] : '';
        $this->path         = (isset($options['path'])) ? $options['path'] : '';
        $this->ignore       = (isset($options['ignore'])) ? (array)$options['ignore'] : $this->phpci->ignore;
    }

    /**
    * Runs PHP Code Sniffer in a specified directory, to a specified standard.
    */
    public function execute()
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

        $tab_width = '';
        if (strlen($this->tab_width)) {
            $tab_width = ' --tab-width='.$this->tab_width;
        }

        $encoding = '';
        if (strlen($this->encoding)) {
            $encoding = ' --encoding='.$this->encoding;
        }

        $cmd = PHPCI_BIN_DIR . 'phpcs %s %s %s %s %s "%s"';
        $success = $this->phpci->executeCommand($cmd, $standard, $suffixes, $ignore, $tab_width, $encoding, $this->phpci->buildPath . $this->path);

        $output = $this->phpci->getLastOutput();

        $matches = array();
        if (preg_match_all('/WARNING/', $output, $matches)) {
            $this->phpci->storeBuildMeta('phpcs-warnings', count($matches[0]));
        }

        $matches = array();
        if (preg_match_all('/ERROR/', $output, $matches)) {
            $this->phpci->storeBuildMeta('phpcs-errors', count($matches[0]));
        }

        return $success;
    }
}
