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
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var array - paths to ignore
     */
    protected $ignore;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $path               = $phpci->buildPath;
        $this->phpci        = $phpci;
        $this->directory    = isset($options['directory']) ? $path . $options['directory'] : $path;
        $this->ignore       = $this->phpci->ignore;

        if (isset($options['ignore'])) {
            $this->ignore = $options['ignore'];
        }
    }

    /**
    * Executes parallel lint
    */
    public function execute()
    {
        list($ignore) = $this->getFlags();

        $phplint = $this->phpci->findBinary('parallel-lint');

        if (!$phplint) {
            $this->phpci->logFailure('Could not find parallel-lint.');
            return false;
        }

        $cmd = $phplint . ' %s "%s"';
        $success = $this->phpci->executeCommand(
            $cmd,
            $ignore,
            $this->directory
        );

        return $success;
    }

    protected function getFlags()
    {
        $ignore = '';
        if (count($this->ignore)) {
            $ignore = ' --exclude ' . implode(' --exclude ', $this->ignore);
        }

        return array($ignore);
    }
}
