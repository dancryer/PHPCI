<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;

use PHPCI;
use PHPCI\Builder;
use PHPCI\Model\Build;

/**
* Composer Plugin - Provides access to Composer functionality.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class Composer implements PHPCI\Plugin, PHPCI\ZeroConfigPlugin
{
    protected $directory;
    protected $action;
    protected $preferDist;
    protected $phpci;

    public static function canExecute($stage, Builder $builder, Build $build)
    {
        $path = $builder->buildPath . '/composer.json';

        if (file_exists($path) && $stage == 'setup') {
            return true;
        }

        return false;
    }

    public function __construct(Builder $phpci, Build $build, array $options = array())
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
        $composerLocation = $this->phpci->findBinary(array('composer', 'composer.phar'));

        if (!$composerLocation) {
            $this->phpci->logFailure('Could not find Composer.');
            return false;
        }
        $cmd = '';
        if (IS_WIN) {
            $cmd = 'php ';
        }
        $cmd .= $composerLocation . ' --no-ansi --no-interaction ';
        $cmd .= ($this->preferDist ? '--prefer-dist' : null) . ' --working-dir="%s" %s';

        return $this->phpci->executeCommand($cmd, $this->directory, $this->action);
    }
}
