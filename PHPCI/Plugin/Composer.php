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
class Composer implements \PHPCI\Plugin
{
    protected $directory;
    protected $action;
    protected $preferDist;
    protected $phpci;

    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $path               = $phpci->buildPath;
        $this->phpci        = $phpci;
        $this->directory    = isset($options['directory']) ? $path . '/' . $options['directory'] : $path;
        $this->action       = isset($options['action']) ? $options['action'] : 'update';
        $this->preferDist   = isset($options['prefer_dist']) ? $options['prefer_dist'] : true;
    }

    /**
    * Executes Composer and runs a specified command (e.g. install / update)
    */
    public function execute()
    {
        $composerLocation = $this->whereIsComposer();

        if (!$composerLocation) {
            $this->phpci->logFailure('Could not find Composer.');
            return false;
        }

        $cmd = $composerLocation . ' --no-ansi --no-interaction '. ($this->preferDist ? '--prefer-dist' : null) .' --working-dir="%s" %s';

        return $this->phpci->executeCommand($cmd, $this->directory, $this->action);
    }

    protected function whereIsComposer()
    {
        if (is_file(PHPCI_DIR . 'composer.phar')) {
            return PHPCI_DIR . 'composer.phar';
        }

        $which = trim(shell_exec('which composer'));

        if (!empty($which)) {
            return $which;
        }

        $which = trim(shell_exec('which composer.phar'));

        if (!empty($which)) {
            return $which;
        }

        return null;
    }
}
