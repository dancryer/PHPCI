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
    protected $dest;
    protected $ignore;
    protected $wipe;
    protected $phpci;
    protected $build;
    protected $includeDir;

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
        $this->build        = $build;
        $this->src          = isset($options['src']) ? $options['src'] : '/';
        $this->dest         = isset($options['dest']) ? $options['dest'] : $path;
        $this->includeDir   = isset($options['include_src_dir']) ?  $options['include_src_dir'] : true;
        $this->wipe         = isset($options['wipe']) ?  (bool)$options['wipe'] : false;
        $this->ignore       = isset($options['respect_ignore']) ?  (bool)$options['respect_ignore'] : false;

        // Sanitise the src directory. We don't want people leaving build dir.
        $this->src = str_replace('..' . DIRECTORY_SEPARATOR, '', $this->src);
        
        // Backwards compatibility
        if (! isset($options['dest']) && isset($options['directory'])) {
            $this->dest = $options['directory'];
        }
    }

    /**
    * Copies files from the root of the build directory into the target folder
    */
    public function execute()
    {

        $build = $this->phpci->buildPath;

        if ($this->dest == $build) {
            return false;
        }

        $this->wipeExistingDirectory();

        $suffix = '';
        
        if ($this->includeDir == false) {

            if (substr($this->src, -1) != DIRECTORY_SEPARATOR) {
                $this->src .= DIRECTORY_SEPARATOR;
            }

            $suffix = "*";
        }

        $cmd = 'mkdir -p "%s" && cp -R "%s"' . $suffix . ' "%s"';
        if (IS_WIN) {
            $cmd = 'mkdir -p "%s" && xcopy /E "%s' . $suffix . '" "%s"';
        }

        $success = $this->phpci->executeCommand($cmd, $this->dest, $this->src, $this->dest);

        $this->deleteIgnoredFiles();

        return $success;
    }

    /**
     * Wipe the destination directory if it already exists.
     * @throws \Exception
     */
    protected function wipeExistingDirectory()
    {
        if ($this->wipe === true && $this->dest != '/' && is_dir($this->dest)) {
            $cmd = 'rm -Rf "%s*"';
            $success = $this->phpci->executeCommand($cmd, $this->dest);

            if (!$success) {
                throw new \Exception(Lang::get('failed_to_wipe', $this->dest));
            }
        }
    }

    /**
     * Delete any ignored files from the build after copying.
     */
    protected function deleteIgnoredFiles()
    {
        if ($this->ignore) {
            foreach ($this->phpci->ignore as $file) {
                $cmd = 'rm -Rf "%s/%s"';
                if (IS_WIN) {
                    $cmd = 'rmdir /S /Q "%s\%s"';
                }
                $this->phpci->executeCommand($cmd, $this->dest, $file);
            }
        }
    }
}
