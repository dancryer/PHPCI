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
* Clean build removes Composer related files and allows PHPCI users to clean up their build directory.
* Useful as a precursor to copy_build.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class CleanBuild extends AbstractExecutingPlugin
{
    protected $remove;

    /**
     * Standard Constructor
     *
     * $options['directory'] Output Directory. Default: %BUILDPATH%
     * $options['filename']  Phar Filename. Default: build.phar
     * $options['regexp']    Regular Expression Filename Capture. Default: /\.php$/
     * $options['stub']      Stub Content. No Default Value
     * Configure the plugin.
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $this->remove = isset($options['remove']) && is_array($options['remove']) ? $options['remove'] : array();
    }

    /**
    * Executes Composer and runs a specified command (e.g. install / update)
    */
    public function execute()
    {
        $cmd = 'rm -Rf "%s"';
        if (IS_WIN) {
            $cmd = 'rmdir /S /Q "%s"';
        }
        $this->executor->executeCommand($cmd, $this->buildPath . 'composer.phar');
        $this->executor->executeCommand($cmd, $this->buildPath . 'composer.lock');

        $success = true;

        foreach ($this->remove as $file) {
            $ok = $this->executor->executeCommand($cmd, $this->buildPath . $file);

            if (!$ok) {
                $success = false;
            }
        }

        return $success;
    }
}
