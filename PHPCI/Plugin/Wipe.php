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
* Wipe Plugin - Wipes a folder
* @author       Claus Due <claus@namelesscoder.net>
* @package      PHPCI
* @subpackage   Plugins
*/
class Wipe implements \PHPCI\Plugin
{
    protected $directory;
    protected $phpci;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $path               = $phpci->buildPath;
        $this->phpci        = $phpci;
        $this->directory    = isset($options['directory']) ? $options['directory'] : $path;
    }

    /**
    * Wipes a directory's contents
    */
    public function execute()
    {
        $build = $this->phpci->buildPath;

        if ($this->directory == $build || empty($this->directory)) {
            return true;
        }
        if (is_dir($this->directory)) {
            $cmd = 'rm -rf %s*';
            $success = $this->phpci->executeCommand($cmd, $this->directory);
        }
        return $success;
    }
}
