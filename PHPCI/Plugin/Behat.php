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
 * Behat BDD Plugin
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Behat implements \PHPCI\Plugin
{
    protected $phpci;
    protected $build;
    protected $features;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci    = $phpci;
        $this->build    = $build;
        $this->features = '';

        if (isset($options['executable'])) {
            $this->executable = $options['executable'];
        } else {
            $this->executable = $this->phpci->findBinary('behat');
        }

        if (!empty($options['features'])) {
            $this->features = $options['features'];
        }
    }

    /**
     * Runs Behat tests.
     */
    public function execute()
    {
        $curdir = getcwd();
        chdir($this->phpci->buildPath);

        $behat = $this->executable;

        if (!$behat) {
            $this->phpci->logFailure('Could not find behat.');
            return false;
        }

        $success = $this->phpci->executeCommand($behat . ' %s', $this->features);
        chdir($curdir);

        return $success;
    }
}
