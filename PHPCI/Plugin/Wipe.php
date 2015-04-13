<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

/**
* Wipe Plugin - Wipes a folder
* @author       Claus Due <claus@namelesscoder.net>
* @package      PHPCI
* @subpackage   Plugins
*/
class Wipe extends AbstractExecutingPlugin
{
    protected $directory;

    /**
     * Configure the plugin.
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $this->directory = isset($options['directory']) ? $options['directory'] : $this->buildPath;
    }

    /**
    * Wipes a directory's contents
    */
    public function execute()
    {
        $build = $this->buildPath;

        if ($this->directory == $build || empty($this->directory)) {
            return true;
        }
        if (is_dir($this->directory)) {
            $cmd = 'rm -Rf "%s"';
            if (IS_WIN) {
                $cmd = 'rmdir /S /Q "%s"';
            }
            return $this->phpci->executeCommand($cmd, $this->directory);
        }
        return true;
    }
}
