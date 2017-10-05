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
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;

/**
* Php Parallel Lint Plugin - Provides access to PHP lint functionality.
* @author       Vaclav Makes <vaclav@makes.cz>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpParallelLint implements \PHPCI\Plugin
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
     * @var string - comma separated list of file extensions
     */
    protected $extensions;

    /**
     * @var bool - enable short tags
     */
    protected $shortTag;

    /**
     * Standard Constructor
     *
     * $options['directory']  Output Directory. Default: %BUILDPATH%
     * $options['filename']   Phar Filename. Default: build.phar
     * $options['extensions'] Filename extensions. Default: php
     * $options['shorttags']  Enable short tags. Default: false
     * $options['stub']       Stub Content. No Default Value
     *
     * @param Builder $phpci
     * @param Build   $build
     * @param array   $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->directory = $phpci->buildPath;
        $this->ignore = $this->phpci->ignore;
        $this->extensions = 'php';
        $this->shortTag = false;

        if (isset($options['directory'])) {
            $this->directory = $phpci->buildPath.$options['directory'];
        }

        if (isset($options['ignore'])) {
            $this->ignore = $options['ignore'];
        }

        if (isset($options['shorttags'])) {
            $this->shortTag = (strtolower($options['shorttags']) == 'true');
        }

        if (isset($options['extensions'])) {
            // Only use if this is a comma delimited list
            $pattern = '/^([a-z]+)(,\ *[a-z]*)*$/';

            if (preg_match($pattern, $options['extensions'])) {
                $this->extensions = str_replace(' ', '', $options['extensions']);
            }
        }
    }

    /**
    * Executes parallel lint
    */
    public function execute()
    {
        list($ignore) = $this->getFlags();

        $phplint = $this->phpci->findBinary('parallel-lint');

        $cmd = $phplint . ' -e %s' . '%s %s "%s"';
        $success = $this->phpci->executeCommand(
            $cmd,
            $this->extensions,
            ($this->shortTag ? ' -s' : ''),
            $ignore,
            $this->directory
        );

        $output = $this->phpci->getLastOutput();

        $matches = array();
        if (preg_match_all('/Parse error\:/', $output, $matches)) {
            $this->build->storeMeta('phplint-errors', count($matches[0]));
        }

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
}
