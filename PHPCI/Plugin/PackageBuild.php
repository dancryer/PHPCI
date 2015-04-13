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
* Create a ZIP or TAR.GZ archive of the entire build.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PackageBuild extends AbstractPlugin
{
    protected $directory;
    protected $filename;
    protected $format;

    /**
     * Configure the plugin.
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $this->directory    = isset($options['directory']) ? $options['directory'] : $this->phpci->buildPath;
        $this->filename     = isset($options['filename']) ? $options['filename'] : 'build';
        $this->format       = isset($options['format']) ?  $options['format'] : 'zip';
    }

    /**
    * Executes Composer and runs a specified command (e.g. install / update)
    */
    public function execute()
    {
        $path = $this->phpci->buildPath;
        $build = $this->build;

        if ($this->directory == $path) {
            return false;
        }

        $filename = str_replace('%build.commit%', $build->getCommitId(), $this->filename);
        $filename = str_replace('%build.id%', $build->getId(), $filename);
        $filename = str_replace('%build.branch%', $build->getBranch(), $filename);
        $filename = str_replace('%project.title%', $build->getProject()->getTitle(), $filename);
        $filename = str_replace('%date%', date('Y-m-d'), $filename);
        $filename = str_replace('%time%', date('Hi'), $filename);
        $filename = preg_replace('/([^a-zA-Z0-9_-]+)/', '', $filename);

        $curdir = getcwd();
        chdir($this->phpci->buildPath);

        if (!is_array($this->format)) {
            $this->format = array($this->format);
        }

        foreach ($this->format as $format) {
            switch ($format) {
                case 'tar':
                    $cmd = 'tar cfz "%s/%s.tar.gz" ./*';
                    break;
                default:
                case 'zip':
                    $cmd = 'zip -rq "%s/%s.zip" ./*';
                    break;
            }

            $success = $this->phpci->executeCommand($cmd, $this->directory, $filename);
        }

        chdir($curdir);

        return $success;
    }
}
