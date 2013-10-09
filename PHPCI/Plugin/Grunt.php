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
* Grunt Plugin - Provides access to grunt functionality.
* @author       Tobias Tom <t.tom@succont.de>
* @package      PHPCI
* @subpackage   Plugins
*/
class Grunt implements \PHPCI\Plugin
{
    protected $directory;
    protected $task;
    protected $preferDist;
    protected $phpci;
    protected $grunt;
    protected $gruntfile;

    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $path               = $phpci->buildPath;
        $this->phpci        = $phpci;
        $this->directory    = isset($options['directory']) ? $path . '/' . $options['directory'] : $path;
        $this->task         = isset($options['task']) ? $options['task'] : null;
        $this->grunt        = isset($options['grunt']) ? $options['grunt'] : $this->phpci->findBinary('grunt');
        $this->gruntfile    = isset($options['gruntfile']) ? $options['gruntfile'] : 'Gruntfile.js';
    }

    /**
    * Executes grunt and runs a specified command (e.g. install / update)
    */
    public function execute()
    {
        // if npm does not work, we cannot use grunt, so we return false
        if ( !$this->phpci->executeCommand( 'cd %s && npm install', $this->directory ) ) {
            return false;
        }

        // build the grunt command
        $cmd = 'cd %s && ' . $this->grunt;
        $cmd .= ' --no-color';
        $cmd .= ' --gruntfile %s';
        $cmd .= ' %s'; // the task that will be executed

        // and execute it
        return $this->phpci->executeCommand($cmd, $this->directory, $this->gruntfile, $this->task);
    }
}
