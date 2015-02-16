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
use PHPCI\Helper\Lang;

/**
* Copy Build Plugin - Copies the entire build to another directory.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class CopyBuild implements \PHPCI\Plugin
{
    protected $directory;
    protected $ignore;
    protected $wipe;
    protected $phpci;
    protected $build;

    /**
     * Set up the plugin, configure options, etc.
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $path               = $phpci->buildPath;
        $this->phpci        = $phpci;
        $this->build = $build;
        $this->directory    = isset($options['directory']) ? $options['directory'] : $path;
        $this->wipe         = isset($options['wipe']) ?  (bool)$options['wipe'] : false;
        $this->ignore       = isset($options['respect_ignore']) ?  (bool)$options['respect_ignore'] : false;
    }

    /**
    * Copies files from the root of the build directory into the target folder
    */
    public function execute()
    {
        $build = $this->phpci->buildPath;

        if ($this->directory == $build) {
            return false;
        }

        $this->wipeExistingDirectory();

        $cmd = 'mkdir -p "%s" && cp -R "%s" "%s"';
        if (IS_WIN) {
            $cmd = 'mkdir -p "%s" && xcopy /E "%s" "%s"';
        }

        $success = $this->phpci->executeCommand($cmd, $this->directory, $build, $this->directory);

        $this->deleteIgnoredFiles();

        return $success;
    }

    /**
     * Wipe the destination directory if it already exists.
     * @throws \Exception
     */
    protected function wipeExistingDirectory()
    {
        if ($this->wipe === true && $this->directory != '/' && is_dir($this->directory)) {
            $cmd = 'rm -Rf "%s*"';
            $success = $this->phpci->executeCommand($cmd, $this->directory);

            if (!$success) {
                throw new \Exception(Lang::get('failed_to_wipe', $this->directory));
            }
        }
    }

    /**
     * Delete any ignored files from the build prior to copying.
     */
    protected function deleteIgnoredFiles()
    {
        if ($this->ignore) {
            foreach ($this->phpci->ignore as $file) {
                $cmd = 'rm -Rf "%s/%s"';
                if (IS_WIN) {
                    $cmd = 'rmdir /S /Q "%s\%s"';
                }
                $this->phpci->executeCommand($cmd, $this->directory, $file);
            }
        }
    }
}
