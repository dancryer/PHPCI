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
 * Behat BDD Plugin
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Behat implements \PHPCI\Plugin
{
    protected $phpci;
    protected $features;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci        = $phpci;
        $this->features = '';

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

        $behat = $this->phpci->findBinary('behat');

        if (!$behat) {
            $this->phpci->logFailure('Could not find behat.');
            return false;
        }

        $success = $this->phpci->executeCommand($behat . ' --no-time --format="failed" %s', $this->features);
        chdir($curdir);

        return $success;
    }
}
