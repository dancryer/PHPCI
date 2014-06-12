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
* PHP CS Fixer - Works with the PHP CS Fixer for testing coding standards.
* @author       Gabriel Baker <gabriel@autonomicpilot.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpCsFixer implements \PHPCI\Plugin
{
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var \PHPCI\Model\Build
     */
    protected $build;

    protected $workingDir = '';
    protected $level      = ' --level=all';
    protected $verbose    = '';
    protected $diff       = '';
    protected $levels     = array('psr0', 'psr1', 'psr2', 'all');

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;

        $this->workingdir = $this->phpci->buildPath;
        $this->buildArgs($options);
    }

    public function execute()
    {
        $curdir = getcwd();
        chdir($this->workingdir);

        $phpcsfixer = $this->phpci->findBinary('php-cs-fixer');

        if (!$phpcsfixer) {
            $this->phpci->logFailure('Could not find php-cs-fixer.');
            return false;
        }

        $cmd = $phpcsfixer . ' fix . %s %s %s';
        $success = $this->phpci->executeCommand($cmd, $this->verbose, $this->diff, $this->level);

        chdir($curdir);

        return $success;
    }

    public function buildArgs($options)
    {
        if (isset($options['verbose']) && $options['verbose']) {
            $this->verbose = ' --verbose';
        }

        if (isset($options['diff']) && $options['diff']) {
            $this->diff = ' --diff';
        }

        if (isset($options['level']) && in_array($options['level'], $this->levels)) {
            $this->level = ' --level='.$options['level'];
        }

        if (isset($options['workingdir']) && $options['workingdir']) {
            $this->workingdir = $this->phpci->buildPath . $options['workingdir'];
        }

    }
}
