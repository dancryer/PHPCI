<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Helper;

use ArrayObject;
use PHPCI\Model\Build;

/**
 * The InterpolatorInterface replaces variables in a string with build-specific information.
 * @package PHPCI\Helper
 */
class Environment extends ArrayObject
{

    /** Initialize the environment.
     *
     * If no array is passed, extract the following variables from the PHP environment: PATH, PATHEXT, TMP and TEMP.
     *
     * @param array $variables
     */
    public function __construct(array $variables = null)
    {
        parent::__construct($variables ? $variables : array());

        $this['PHPCI'] = "1";
        $this['TERM'] = 'ansi-generic';
        $this['LANG'] = 'C';

        if ($variables === null) {
            foreach (array('PATH', 'PATHEXT', 'TMP', 'TEMP', 'HOME') as $name) {
                $value = getenv($name);
                if (!empty($value)) {
                    $this[$name] = $value;
                }
            }
        }
    }

    /** Adds PHPCI variables for the given build
     *
     * @param Build $build
     * @param string $buildPath
     */
    public function addBuildVariables(Build $build, $buildPath)
    {
        $buildVars = array(
            'COMMIT' => $build->getCommitId(),
            'SHORT_COMMIT' => substr($build->getCommitId(), 0, 7),
            'COMMIT_EMAIL' => $build->getCommitterEmail(),
            'COMMIT_URI' => $build->getCommitLink(),
            'BRANCH' => $build->getBranch(),
            'BRANCH_URI' => $build->getBranchLink(),
            'PROJECT' => $build->getProjectId(),
            'BUILD' => $build->getId(),
            'PROJECT_TITLE' => $build->getProjectTitle(),
            'PROJECT_URI' => PHPCI_URL . "project/view/" . $build->getProjectId(),
            'BUILD_PATH' => $buildPath,
            'BUILD_URI' => PHPCI_URL . "build/view/" . $build->getId()
        );

        foreach ($buildVars as $name => $value) {
            $this['PHPCI_' . $name] = $this[$name] = $value;
        }

        $this->addPath($buildPath);
    }

    /** Get the list of paths from PATH.
     *
     * @return array
     */
    public function getPaths()
    {
        return isset($this['PATH']) ? explode(PATH_SEPARATOR, $this['PATH']) : array();
    }

    /** Add a path to PATH.
     *
     * @param string $path The path to add.
     * @param string $before If provided and found, $path will be inserted just before this path.
     */
    public function addPath($path, $before = null)
    {
        $paths = $this->getPaths();

        $index = $before !== null ? array_search($before, $paths) : false;
        if ($index === false) {
            $paths[] = $path;
        } else {
            array_splice($paths, $index, 0, array($path));
        }

        $this['PATH'] = implode(PATH_SEPARATOR, $paths);
    }

    /** Normalise configuration data into a dictionary.
     *
     * @param mixed $config
     * @return array
     */
    public function normaliseConfig($config)
    {
        $vars = array();
        $this->normaliseEntry($vars, $config);
        return $vars;
    }

    /** Store the variable of a configuration entry into the dictionary.
     *
     * @param array $vars
     * @param array|string $entry
     */
    protected function normaliseEntry(&$vars, $entry)
    {
        if (is_array($entry)) {
            foreach ($entry as $key => $value) {
                if (is_string($key)) {
                    $vars[$key] = $value;
                } else {
                    $this->normaliseEntry($vars, $value);
                }
            }
        } elseif (is_string($entry)) {
            list($key, $value) = explode("=", $entry);
            $vars[trim($key)] = trim($value);
        }
    }
}
