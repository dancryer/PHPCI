<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

/**
* PHP Mess Detector Plugin - Allows PHP Mess Detector testing.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpMessDetector implements \PHPCI\Plugin
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
     * @var string, based on the assumption the root may not hold the code to be
     * tested, exteds the base path only if the provided path is relative. Absolute
     * paths are used verbatim
     */
    protected $path;

    /**
     * @var array - paths to ignore
     */
    protected $ignore;

    /**
     * Array of PHPMD rules. Can be one of the builtins (codesize, unusedcode, naming, design, controversial)
     * or a filenname (detected by checking for a / in it), either absolute or relative to the project root.
     * @var array
     */
    protected $rules;

    /**
     * @param \PHPCI\Builder $phpci
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->suffixes = array('php');
        $this->ignore = $phpci->ignore;
        $this->path = '';
        $this->rules = array('codesize', 'unusedcode', 'naming');

        if (!empty($options['path'])) {
            $this->path = $options['path'];
        }

        foreach (array('rules', 'ignore', 'suffixes') as $key) {
            $this->overrideSetting($options, $key);
        }
    }

    /**
     * Runs PHP Mess Detector in a specified directory.
     */
    public function execute()
    {
        $ignore = '';
        if (count($this->ignore)) {
            $ignore = ' --exclude ' . implode(',', $this->ignore);
        }

        $suffixes = '';
        if (count($this->suffixes)) {
            $suffixes = ' --suffixes ' . implode(',', $this->suffixes);
        }

        foreach ($this->rules as &$rule) {
            if (strpos($rule, '/') !== false) {
                $rule = $this->phpci->buildPath . $rule;
            }
        }

        $phpmd = $this->phpci->findBinary('phpmd');

        if (!$phpmd) {
            $this->phpci->logFailure('Could not find phpmd.');
            return false;
        }
        
        $path = $this->phpci->buildPath . $this->path;
        if (!empty($this->path) && $this->path{0} == '/') {
            $path = $this->path;
        }

        $cmd = $phpmd . ' "%s" text %s %s %s';
        $success = $this->phpci->executeCommand(
            $cmd,
            $path,
            implode(',', $this->rules),
            $ignore,
            $suffixes
        );

        $errors = count(array_filter(explode(PHP_EOL, trim($this->phpci->getLastOutput()))));
        $this->build->storeMeta('phpmd-warnings', $errors);

        return $success;
    }

    protected function overrideSetting($options, $key)
    {
        if (isset($options[$key]) && is_array($options[$key])) {
            $this->{$key} = $options[$key];
        }
    }
}
