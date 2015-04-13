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
use PHPCI\Helper\Lang;

/**
* Composer Plugin - Provides access to Composer functionality.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class Composer extends AbstractExecutingPlugin implements PHPCI\ZeroConfigPlugin
{
    protected $directory;
    protected $action;
    protected $preferDist;

    /**
     * Check if this plugin can be executed.
     * @param $stage
     * @param Builder $builder
     * @param Build $build
     * @return bool
     */
    public static function canExecute($stage, Builder $builder, Build $build)
    {
        $path = $builder->buildPath . '/composer.json';

        if (file_exists($path) && $stage == 'setup') {
            return true;
        }

        return false;
    }

    /**
     * Configure the plugin.
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $path = $this->buildPath;
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
        $composerLocation = $this->executor->findBinary(array('composer', 'composer.phar'));

        $cmd = '';

        if (IS_WIN) {
            $cmd = 'php ';
        }

        $cmd .= $composerLocation . ' --no-ansi --no-interaction ';

        if ($this->preferDist) {
            $this->logger->log('Using --prefer-dist flag');
            $cmd .= '--prefer-dist';
        } else {
            $this->logger->log('Using --prefer-source flag');
            $cmd .= '--prefer-source';
        }

        $cmd .= ' --working-dir="%s" %s';

        return $this->executor->executeCommand($cmd, $this->directory, $this->action);
    }
}
