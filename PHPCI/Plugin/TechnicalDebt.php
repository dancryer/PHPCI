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
class TechnicalDebt extends AbstractPlugin implements PHPCI\ZeroConfigPlugin
{
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
     * @var int
     */
    protected $allowed_warnings;

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
     * Configure the plugin.
     *
     * @param array $options
     */
    protected function setOptions(array $options)
    {
        $this->suffixes = array('php');
        $this->directory = $this->buildPath;
        $this->path = '';
        $this->ignore = $this->phpci->ignore;
        $this->allowed_warnings = 0;
        $this->allowed_errors = 0;
        $this->searches = array('TODO', 'FIXME', 'TO DO', 'FIX ME');

        if (isset($options['searches']) && is_array($options['searches'])) {
            $this->searches = $options['searches'];
        }

        if (isset($options['zero_config']) && $options['zero_config']) {
            $this->allowed_warnings = -1;
            $this->allowed_errors = -1;
        }

        foreach (array('directory', 'path', 'ignore', 'allowed_warnings', 'allowed_errors') as $key) {
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

        list($errorCount, $data) = $this->getErrorList();

        $this->logger->log("Found $errorCount instances of " . implode(', ', $this->searches));

        $this->build->storeMeta('technical_debt-warnings', $errorCount);
        $this->build->storeMeta('technical_debt-data', $data);

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
            if (stripos($filePath, '/.') !== false) {
                $skipFile = true;
            }

            if ($skipFile == false) {
                $files[] = $file->getRealPath();
            }
        }

        $files = array_filter(array_unique($files));
        $errorCount = 0;
        $data = array();

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
                    $this->logger->log("Found $search on line $lineNumber of $file:\n$content");

                    $fileName = str_replace($this->directory, '', $file);
                    $data[] = array(
                        'file' => $fileName,
                        'line' => $lineNumber,
                        'message' => $content
                    );

                    $this->build->reportError($this->phpci, $fileName, $lineNumber, $content);
                }
            }
        }

        return array( $errorCount, $data );
    }
}
