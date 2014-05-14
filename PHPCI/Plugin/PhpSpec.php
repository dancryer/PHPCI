<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI;
use PHPCI\Builder;
use PHPCI\Model\Build;

/**
* PHP Spec Plugin - Allows PHP Spec testing.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpSpec implements PHPCI\Plugin
{
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var \PHPCI\Model\Build
     */
    protected $build;

    /**
     * @var array
     */
    protected $options;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->options = $options;
    }

    /**
    * Runs PHP Spec tests.
    */
    public function execute()
    {
        $curdir = getcwd();
        chdir($this->phpci->buildPath);

        $phpspec = $this->phpci->findBinary(array('phpspec', 'phpspec.php'));

        if (!$phpspec) {
            $this->phpci->logFailure('Could not find phpspec.');
            return false;
        }

        $success = $this->phpci->executeCommand($phpspec . ' --format=pretty --no-code-generation run');

        chdir($curdir);
        
        return $success;
    }
}
