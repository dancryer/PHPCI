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
* PHP Spec Plugin - Allows PHP Spec testing.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpSpec implements \PHPCI\Plugin
{
    protected $phpci;
    protected $bootstrap;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci        = $phpci;

        if (!empty($options['bootstrap'])) {
            $this->bootstrap = $this->buildPath . $options['bootstrap'];
        }
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

        if ($this->bootstrap) {
            $success = $this->phpci->executeCommand($phpspec . ' -f d');
        } else {
            $success = $this->phpci->executeCommand($phpspec . ' -f d --bootstrap "%s"', $this->bootstrap);
        }

        chdir($curdir);
        
        return $success;
    }
}
