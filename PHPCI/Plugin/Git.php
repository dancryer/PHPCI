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
 * Git plugin.
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Git implements \PHPCI\Plugin
{
    protected $phpci;
    protected $build;
    protected $actions = array();

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->actions = $options;
    }


    public function execute()
    {
        $buildPath = $this->phpci->buildPath;

        // Check if there are any actions to be run for the branch we're running on:
        if (!array_key_exists($this->build->getBranch(), $this->actions)) {
            return true;
        }

        // If there are, run them:
        $curdir = getcwd();
        chdir($buildPath);

        $success = true;
        foreach ($this->actions[$this->build->getBranch()] as $action => $options) {
            if (!$this->runAction($action, $options)) {
                $success = false;
                break;
            }
        }

        chdir($curdir);

        return $success;
    }

    protected function runAction($action, $options = array())
    {
        // If options isn't an array, it should be.
        if (!is_array($options)) {
            $options = array();
        }

        // Handle git merges.
        if ($action == 'merge' && array_key_exists('branch', $options)) {
            $cmd = 'git checkout %s && git merge ' . $this->build->getBranch();
            return $this->phpci->executeCommand($cmd, $this->directory, $options['branch']);
        }

        // Handle tagging:
        if ($action == 'tag') {
            $tagName = date('Ymd-His');
            $message = 'Tag created by PHPCI: ' . date('Y-m-d H:i:s');

            if (array_key_exists('name', $options)) {
                $tagName = $this->phpci->interpolate($options['name']);
            }

            if (array_key_exists('message', $options)) {
                $message = $this->phpci->interpolate($options['message']);
            }

            $cmd = 'git tag %s -m "%s"';
            return $this->phpci->executeCommand($cmd, $tagName, $message);
        }

        // Handle pull:
        if ($action == 'pull') {
            $branch = $this->build->getBranch();
            $remote = 'origin';

            if (array_key_exists('branch', $options)) {
                $branch = $this->phpci->interpolate($options['branch']);
            }

            if (array_key_exists('remote', $options)) {
                $remote = $this->phpci->interpolate($options['remote']);
            }

            return $this->phpci->executeCommand('git pull %s %s', $remote, $branch);
        }

        // Handle push:
        if ($action == 'push') {
            $branch = $this->build->getBranch();
            $remote = 'origin';

            if (array_key_exists('branch', $options)) {
                $branch = $this->phpci->interpolate($options['branch']);
            }

            if (array_key_exists('remote', $options)) {
                $remote = $this->phpci->interpolate($options['remote']);
            }

            return $this->phpci->executeCommand('git push %s %s', $remote, $branch);
        }

        return false;
    }
}
