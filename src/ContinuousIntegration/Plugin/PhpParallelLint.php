<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Plugin;

use Kiboko\Component\ContinuousIntegration\Builder;
use Kiboko\Component\ContinuousIntegration\Helper\Lang;
use Kiboko\Component\ContinuousIntegration\Model\Build;

/**
* Php Parallel Lint Plugin - Provides access to PHP lint functionality.
* @author       Vaclav Makes <vaclav@makes.cz>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpParallelLint implements \Kiboko\Component\ContinuousIntegration\Plugin
{
    /**
     * @var \Kiboko\Component\ContinuousIntegration\Builder
     */
    protected $phpci;

    /**
     * @var \Kiboko\Component\ContinuousIntegration\Model\Build
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
     * Standard Constructor
     *
     * $options['directory']  Output Directory. Default: %BUILDPATH%
     * $options['filename']   Phar Filename. Default: build.phar
     * $options['extensions'] Filename extensions. Default: php
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

        if (isset($options['directory'])) {
            $this->directory = $phpci->buildPath.$options['directory'];
        }

        if (isset($options['ignore'])) {
            $this->ignore = $options['ignore'];
        }

        if (isset($options['extensions'])) {
            // Only use if this is a comma delimited list
            $pattern = '/^[a-z]*,\ *[a-z]*$/';

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

        $cmd = $phplint . ' -e %s' . ' %s "%s"';
        $success = $this->phpci->executeCommand(
            $cmd,
            $this->extensions,
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
