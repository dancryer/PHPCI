<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Helper\Lang;

/**
* PHP CS Fixer - Works with the PHP CS Fixer for testing coding standards.
* @author       Gabriel Baker <gabriel@autonomicpilot.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpCsFixer extends AbstractExecutingPlugin
{
    protected $workingDir = '';
    protected $level      = ' --level=all';
    protected $verbose    = '';
    protected $diff       = '';
    protected $levels     = array('psr0', 'psr1', 'psr2', 'all');

    /**
     * Configure the plugin.
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $this->workingdir = $this->buildPath;

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
            $this->workingdir .= $options['workingdir'];
        }
    }

    /**
     * Run PHP CS Fixer.
     * @return bool
     */
    public function execute()
    {
        $curdir = getcwd();
        chdir($this->workingdir);

        $phpcsfixer = $this->executor->findBinary('php-cs-fixer');

        $cmd = $phpcsfixer . ' fix . %s %s %s';
        $success = $this->executor->executeCommand($cmd, $this->verbose, $this->diff, $this->level);

        chdir($curdir);

        return $success;
    }
}
