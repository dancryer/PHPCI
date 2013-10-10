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
* Php Parallel Lint Plugin - Provides access to PHP lint functionality.
* @author       Vaclav Makes <vaclav@makes.cz>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpParallelLint implements \PHPCI\Plugin
{
    protected $directory;
    protected $preferDist;
    protected $phpci;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $path               = $phpci->buildPath;
        $this->phpci        = $phpci;
        $this->directory    = isset($options['directory']) ? $path . $options['directory'] : $path;
    }

    /**
    * Executes parallel lint
    */
    public function execute()
    {
        // build the parallel lint command
        $cmd = "run %s";

        // and execute it
        return $this->phpci->executeCommand(PHPCI_BIN_DIR . $cmd, $this->directory);
    }
}
