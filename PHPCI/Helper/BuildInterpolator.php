<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Helper;

use PHPCI\Model\Build;

/**
 * The BuildInterpolator class replaces variables in a string with build-specific information.
 * @package PHPCI\Helper
 */
class BuildInterpolator
{
    /**
     * An array of key => value pairs that will be used for
     * interpolation and environment variables
     * @var mixed[]
     * @see setupInterpolationVars()
     */
    protected $interpolation_vars = array();

    /**
     * Sets the variables that will be used for interpolation.
     *
     * @param Build $build
     * @param string $buildPath
     * @param string $phpCiUrl
     */
    public function setupInterpolationVars(Build $build, $buildPath, $phpCiUrl)
    {
        $vars = array(
            'COMMIT'        => $build->getCommitId(),
            'SHORT_COMMIT'  => substr($build->getCommitId(), 0, 7),
            'COMMIT_EMAIL'  => $build->getCommitterEmail(),
            'COMMIT_URI'    => $build->getCommitLink(),
            'BRANCH'        => $build->getBranch(),
            'BRANCH_URI'    => $build->getBranchLink(),
            'PROJECT'       => $build->getProjectId(),
            'BUILD'         => $build->getId(),
            'PROJECT_TITLE' => $build->getProjectTitle(),
            'PROJECT_URI'   => $phpCiUrl . "project/view/" . $build->getProjectId(),
            'BUILD_PATH'    => $buildPath,
            'BUILD_URI'     => $phpCiUrl . "build/view/" . $build->getId()
        );

        $this->interpolation_vars['%PHPCI%'] = 1;
        putenv('PHPCI=1');

        foreach($vars as $name => $value) {
            $this->interpolation_vars['%' . $name . '%'] = $value;
            $this->interpolation_vars['%PHPCI_' . $name . '%'] = $value;
            putenv(sprintf('PHPCI_%s=%s', $name, $value));
        }
    }

    /**
     * Replace every occurrence of the interpolation vars in the given string
     * Example: "This is build %PHPCI_BUILD%" => "This is build 182"
     *
     * @param string $input
     *
     * @return string
     */
    public function interpolate($input)
    {
        $keys = array_keys($this->interpolation_vars);
        $values = array_values($this->interpolation_vars);
        return str_replace($keys, $values, $input);
    }
}
