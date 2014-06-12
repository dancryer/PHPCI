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
    protected $build;

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
        $path = $phpci->buildPath;
        $this->phpci = $phpci;
        $this->build = $build;
        $this->directory = $path;
        $this->action = 'install';
        $this->preferDist = false;

        if (array_key_exists('directory', $options)) {
            $this->directory = $path . '/' . $options['directory'];
        }

        if (array_key_exists('action', $options)) {
            $this->action = $options['action'];
        }

        if (array_key_exists('prefer_dist', $options)) {
            $this->preferDist = (bool)$options['prefer_dist'];
        }
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

        if ($this->preferDist) {
            $this->phpci->log('Using --prefer-dist flag');
            $cmd .= '--prefer-dist';
        } else {
            $this->phpci->log('Using --prefer-source flag');
            $cmd .= '--prefer-source';
        }

        $cmd .= ' --working-dir="%s" %s';

        return $this->phpci->executeCommand($cmd, $this->directory, $this->action);
    }
}
