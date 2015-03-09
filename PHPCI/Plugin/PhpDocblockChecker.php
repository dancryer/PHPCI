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
* PHP Docblock Checker Plugin - Checks your PHP files for appropriate uses of Docblocks
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpDocblockChecker implements PHPCI\Plugin, PHPCI\ZeroConfigPlugin
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
     * @var string Based on the assumption the root may not hold the code to be
     * tested, extends the build path.
     */
    protected $path;

    /**
     * @var array - paths to ignore
     */
    protected $ignore;

    protected $skipClasses = false;
    protected $skipMethods = false;

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

    /**
     * Set up the plugin, configure options, etc.
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->ignore = $phpci->ignore;
        $this->path = '';
        $this->allowed_warnings = 0;

        if (isset($options['zero_config']) && $options['zero_config']) {
            $this->allowed_warnings = -1;
        }

        if (array_key_exists('skip_classes', $options)) {
            $this->skipClasses = true;
        }

        if (array_key_exists('skip_methods', $options)) {
            $this->skipMethods = true;
        }

        if (!empty($options['path'])) {
            $this->path = $options['path'];
        }

        if (array_key_exists('allowed_warnings', $options)) {
            $this->allowed_warnings = (int)$options['allowed_warnings'];
        }
    }

    /**
     * Runs PHP Mess Detector in a specified directory.
     */
    public function execute()
    {
        // Check that the binary exists:
        $checker = $this->phpci->findBinary('phpdoccheck');

        if (!$checker) {
            $this->phpci->logFailure(PHPCI\Helper\Lang::get('could_not_find', 'phpdoccheck'));
            return false;
        }

        // Build ignore string:
        $ignore = '';
        if (count($this->ignore)) {
            $ignore = ' --exclude="' . implode(',', $this->ignore) . '"';
        }

        // Are we skipping any checks?
        $add = '';
        if ($this->skipClasses) {
            $add .= ' --skip-classes';
        }

        if ($this->skipMethods) {
            $add .= ' --skip-methods';
        }

        // Build command string:
        $path = $this->phpci->buildPath . $this->path;
        $cmd = $checker . ' --json --directory="%s"%s%s';

        // Disable exec output logging, as we don't want the XML report in the log:
        $this->phpci->logExecOutput(false);

        // Run checker:
        $this->phpci->executeCommand(
            $cmd,
            $path,
            $ignore,
            $add
        );

        // Re-enable exec output logging:
        $this->phpci->logExecOutput(true);

        $output = json_decode($this->phpci->getLastOutput(), true);
        $errors = count($output);
        $success = true;

        $this->build->storeMeta('phpdoccheck-warnings', $errors);
        $this->build->storeMeta('phpdoccheck-data', $output);
        $this->reportErrors($output);

        if ($this->allowed_warnings != -1 && $errors > $this->allowed_warnings) {
            $success = false;
        }

        return $success;
    }

    /**
     * Report all of the errors we've encountered line-by-line.
     * @param $output
     */
    protected function reportErrors($output)
    {
        foreach ($output as $error) {
            $message = 'Class ' . $error['class'] . ' does not have a Docblock comment.';

            if ($error['type'] == 'method') {
                $message = 'Method ' . $error['class'] . '::' . $error['method'] . ' does not have a Docblock comment.';
            }

            $this->build->reportError($this->phpci, $error['file'], $error['line'], $message);
        }
    }
}
