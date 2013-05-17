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
* Composer Plugin - Provides access to Composer functionality.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class CopyBuild implements \PHPCI\Plugin
{
    protected $directory;
    protected $phpci;

    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $path               = $phpci->buildPath;
        $this->phpci        = $phpci;
        $this->directory    = isset($options['directory']) ? $options['directory'] : $path;
        $this->ignore       = isset($options['respect_ignore']) ?  (bool)$options['respect_ignore'] : false;
    }

    /**
    * Executes Composer and runs a specified command (e.g. install / update)
    */
    public function execute()
    {
        $build = $this->phpci->buildPath;

        if ($this->directory == $build) {
            return false;
        }

        $cmd = 'mkdir -p "%s" && cp -Rf "%s"* "%s/"';
        $success = $this->phpci->executeCommand($cmd, $this->directory, $build, $this->directory);

        if ($this->ignore) {
            foreach ($this->phpci->ignore as $file) {
                $cmd = 'rm -Rf "%s/%s"';
                $this->phpci->executeCommand($cmd, $this->directory, $file);
            }
        }

        return $success;
    }
}
