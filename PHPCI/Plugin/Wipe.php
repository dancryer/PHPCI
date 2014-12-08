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
* Wipe Plugin - Wipes a folder
* @author       Claus Due <claus@namelesscoder.net>
* @package      PHPCI
* @subpackage   Plugins
*/
class Wipe implements \PHPCI\Plugin
{
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var \PHPCI\Model\Build
     */
    protected $build;

    protected $directory;

    /**
     * Set up the plugin, configure options, etc.
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $path               = $phpci->buildPath;
        $this->phpci        = $phpci;
        $this->build = $build;
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
            $cmd = 'rm -Rf "%s"';
            if (IS_WIN) {
                $cmd = 'rmdir /S /Q "%s"';
            }
            $success = $this->phpci->executeCommand($cmd, $this->directory);
        }
        return $success;
    }
}
