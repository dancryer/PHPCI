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
* PHP CS Fixer - Works with the PHP CS Fixer for testing coding standards.
* @author       Gabriel Baker <gabriel@autonomicpilot.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpCsFixer implements \PHPCI\Plugin
{
    protected $phpci;

    protected $args = '';

    protected $workingDir = '';
    protected $level      = 'all';
    protected $dryRun     = true;
    protected $verbose    = false;
    protected $diff       = false;
    protected $levels     = array('psr0', 'psr1', 'psr2', 'all');

    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $this->phpci = $phpci;
        $this->workingdir = $this->phpci->buildPath;
        $this->buildArgs($options);
    }

    public function execute()
    {
        $success = false;

        $curdir = getcwd();
        chdir($this->workingdir);

        $cmd = PHPCI_BIN_DIR . 'php-cs-fixer fix . %s';
        $success = $this->phpci->executeCommand($cmd, $this->args);

        chdir($curdir);

        return $success;
    }

    public function buildArgs($options)
    {
        $argstring = "";

        if ( array_key_exists('verbose', $options) && $options['verbose'] )
        {
            $this->verbose = true;
            $this->args .= ' --verbose';
        }

        if ( array_key_exists('diff', $options) && $options['diff'] )
        {
            $this->diff = true;
            $this->args .= ' --diff';
        }

        if ( array_key_exists('level', $options) && in_array($options['level'], $this->levels) )
        {
            $this->level = $options['level'];
            $this->args .= ' --level='.$options['level'];
        }

        if ( array_key_exists('dryrun', $options) && $options['dryrun'] )
        {
            $this->dryRun = true;
            $this->args .= ' --dry-run';
        }

        if ( array_key_exists('workingdir', $options)
            && $options['workingdir']
            && is_dir($this->phpci->buildPath.$options['workingdir']) )
        {
            $this->workingdir = $this->phpci->buildPath.$options['workingdir'];
        }

    }
}