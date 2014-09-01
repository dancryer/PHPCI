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

/**
* Create a ZIP or TAR.GZ archive of the entire build.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PackageBuild implements \PHPCI\Plugin
{
    protected $directory;
    protected $filename;
    protected $format;
    protected $phpci;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $path                  = $phpci->buildPath;
        $this->build           = $build;
        $this->phpci           = $phpci;
        $this->directory       = isset($options['directory']) ? $options['directory'] : $path;
        $this->filename        = isset($options['filename']) ? $options['filename'] : 'build';
        $this->format          = isset($options['format']) ?  $options['format'] : 'zip';
		$this->allowedBranches = isset($options['allowedBranches']) ?  $options['allowedBranches'] : NULL;
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

		if ($this->allowedBranches === NULL || !preg_match('/' . $this->allowedBranches . '/i', $build->getBranch())) {
			return false;
		}

		$directory = $this->replaceVariables($this->directory, $build, FALSE);
		$filename = $this->replaceVariables($this->filename, $build);

		if (!is_dir($directory)) {
			mkdir($directory, 0777, TRUE);
		}

        $curdir = getcwd();
        chdir($this->phpci->buildPath);

        if (!is_array($this->format)) {
            $this->format = array($this->format);
        }

        foreach ($this->format as $format) {
            switch($format)
            {
                case 'tar':
                    $cmd = 'tar cfz "%s/%s.tar.gz" ./*';
                    break;
                default:
                case 'zip':
                    $cmd = 'zip -rq "%s/%s.zip" ./*';
                    break;
            }

            $success = $this->phpci->executeCommand($cmd, $directory, $filename);
        }

        chdir($curdir);

        return $success;
    }

	/**
	 * @param string $string
	 * @param Build $build
	 * @param boolean $stripSpecialChars
	 * @return mixed
	 */
	protected function replaceVariables($string, Build $build, $stripSpecialChars = TRUE) {
		$branch = $build->getBranch();
		$branch = str_replace('/', '-', $branch);

		$replacedString = str_replace('%build.commit%', $build->getCommitId(), $string);
		$replacedString = str_replace('%build.id%', $build->getId(), $replacedString);
		$replacedString = str_replace('%build.branch%', $branch, $replacedString);
		$replacedString = str_replace('%project.title%', $build->getProject()->getTitle(), $replacedString);
		$replacedString = str_replace('%date%', date('Y-m-d'), $replacedString);
		$replacedString = str_replace('%time%', date('Hi'), $replacedString);
		if ($stripSpecialChars === TRUE) {
			$replacedString = preg_replace('/([^a-zA-Z0-9_-]+)/', '', $replacedString);
		}
		return $replacedString;
	}
}
