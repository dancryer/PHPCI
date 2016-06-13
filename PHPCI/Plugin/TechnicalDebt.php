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
* Technical Debt Plugin - Checks for existence of "TODO", "FIXME", etc.
*
* @author       James Inman <james@jamesinman.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class TechnicalDebt implements PHPCI\Plugin, PHPCI\ZeroConfigPlugin
{
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var array
     */
    protected $suffixes;

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var int
     */
    protected $allowed_errors;

    /**
     * @var string, based on the assumption the root may not hold the code to be
     * tested, extends the base path
     */
    protected $path;

    /**
     * @var array - paths to ignore
     */
    protected $ignore;

    /**
     * @var array - terms to search for
     */
    protected $searches;


    /**
     * Check if this plugin can be executed.
     *
     * @param $stage
     * @param Builder $builder
     * @param Build $build
     * @return bool
     */
    public static function canExecute($stage, Builder $builder, Build $build)
    {
        if ($stage == 'test') {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPCI\Builder     $phpci
     * @param \PHPCI\Model\Build $build
     * @param array              $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->suffixes = array('php');
        $this->directory = $phpci->buildPath;
        $this->path = '';
        $this->ignore = $this->phpci->ignore;
        $this->allowed_errors = 0;
        $this->searches = array('TODO', 'FIXME', 'TO DO', 'FIX ME');

        if (isset($options['searches']) && is_array($options['searches'])) {
            $this->searches = $options['searches'];
        }

        if (isset($options['zero_config']) && $options['zero_config']) {
            $this->allowed_errors = -1;
        }

        $this->setOptions($options);
    }

    /**
     * Handle this plugin's options.
     * @param $options
     */
    protected function setOptions($options)
    {
        foreach (array('directory', 'path', 'ignore', 'allowed_errors') as $key) {
            if (array_key_exists($key, $options)) {
                $this->{$key} = $options[$key];
            }
        }
    }

    /**
    * Runs the plugin
    */
    public function execute()
    {
        $success = true;
        $this->phpci->logExecOutput(false);

        $errorCount = $this->getErrorList();

        $this->phpci->log("Found $errorCount instances of " . implode(', ', $this->searches));

        $this->build->storeMeta('technical_debt-warnings', $errorCount);

        if ($this->allowed_errors != -1 && $errorCount > $this->allowed_errors) {
            $success = false;
        }

        return $success;
    }

    /**
     * Gets the number and list of errors returned from the search
     *
     * @return array
     */
    public function getErrorList()
    {
        $dirIterator = new \RecursiveDirectoryIterator($this->directory);
        $iterator = new \RecursiveIteratorIterator($dirIterator, \RecursiveIteratorIterator::SELF_FIRST);
        $files = array();

        $ignores = $this->ignore;
        $ignores[] = 'phpci.yml';
        $ignores[] = '.phpci.yml';

        foreach ($iterator as $file) {
            $filePath = $file->getRealPath();
            $skipFile = false;
            foreach ($ignores as $ignore) {
                if (stripos($filePath, $ignore) !== false) {
                    $skipFile = true;
                    break;
                }
            }

            // Ignore hidden files, else .git, .sass_cache, etc. all get looped over
            if (stripos($filePath, DIRECTORY_SEPARATOR . '.') !== false) {
                $skipFile = true;
            }

            if ($skipFile == false) {
                $files[] = $file->getRealPath();
            }
        }

        $files = array_filter(array_unique($files));
        $errorCount = 0;

        foreach ($files as $file) {
            foreach ($this->searches as $search) {
                $fileContent = file_get_contents($file);
                $allLines = explode(PHP_EOL, $fileContent);
                $beforeString = strstr($fileContent, $search, true);

                if (false !== $beforeString) {
                    $lines = explode(PHP_EOL, $beforeString);
                    $lineNumber = count($lines);
                    $content = trim($allLines[$lineNumber - 1]);

                    $errorCount++;

                    $fileName = str_replace($this->directory, '', $file);

                    $this->build->reportError(
                        $this->phpci,
                        'technical_debt',
                        $content,
                        PHPCI\Model\BuildError::SEVERITY_LOW,
                        $fileName,
                        $lineNumber
                    );
                }
            }
        }

        return $errorCount;
    }
}
