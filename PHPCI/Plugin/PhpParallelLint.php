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
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;

/**
* Php Parallel Lint Plugin - Provides access to PHP lint functionality.
* @author       Vaclav Makes <vaclav@makes.cz>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpParallelLint implements \PHPCI\Plugin, PHPCI\ZeroConfigPlugin
{
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var \PHPCI\Model\Build
     */
    protected $build;

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var array - paths to ignore
     */
    protected $ignore;

    /**
     * Standard Constructor
     *
     * $options['directory'] Output Directory. Default: %BUILDPATH%
     * $options['filename']  Phar Filename. Default: build.phar
     * $options['regexp']    Regular Expression Filename Capture. Default: /\.php$/
     * $options['stub']      Stub Content. No Default Value
     *
     * @param Builder $phpci
     * @param Build   $build
     * @param array   $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci        = $phpci;
        $this->build        = $build;
        $this->directory    = $phpci->buildPath;
        $this->ignore       = $this->phpci->ignore;

        if (isset($options['directory'])) {
            $this->directory = $phpci->buildPath.$options['directory'];
        }

        if (isset($options['ignore'])) {
            $this->ignore = $options['ignore'];
        }
    }

    /**
    * Executes parallel lint
    */
    public function execute()
    {
        list($ignore) = $this->getFlags();

        $phplint = $this->phpci->findBinary('parallel-lint');

        $cmd = $phplint . ' %s "%s"';
        $success = $this->phpci->executeCommand(
            $cmd,
            $ignore,
            $this->directory
        );

        $output = $this->phpci->getLastOutput();

        $errors = explode('Parse error: ', $output);
        array_shift($errors);

        $this->build->storeMeta('phplint-errors', count($errors));

        $data = array();
        foreach ($errors as $line) {
            $fileName = substr($line, 0, strpos($line, ':'));

            $lineNumber = 0;
            
            $matches = array();
            preg_match('/:([0-9]+)/', $line, $matches);

            if (isset($matches[1])) {
                $lineNumber = $matches[1];
            }

            $message = trim(substr($line, strpos($line, ' ')));

            $data[] = array(
                'file' => $fileName,
                'line' => $lineNumber,
                'type' => 'ERROR',
                'message' => $message
            );

            $this->build->reportError($this->phpci, $fileName, $message, 'Lint: ' . $message);

        }

        $this->build->storeMeta('phplint-data', $data);

        return $success;
    }

    /**
     * Produce an argument string for PHP Parallel Lint.
     * @return array
     */
    protected function getFlags()
    {
        $ignoreFlags = array();
        foreach ($this->ignore as $ignoreDir) {
            $ignoreFlags[] = '--exclude "' . $this->phpci->buildPath . $ignoreDir . '"';
        }
        $ignore = implode(' ', $ignoreFlags);

        return array($ignore);
    }
   
    /**
     * Check if this plugin can be executed.
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
}
