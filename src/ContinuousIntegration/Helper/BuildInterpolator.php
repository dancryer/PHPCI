<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Helper;

use Kiboko\Component\ContinuousIntegration\Model\Build;

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
     * @param Build $build
     * @param string $buildPath
     * @param string $phpCiUrl
     */
    public function setupInterpolationVars(Build $build, $buildPath, $phpCiUrl)
    {
        $this->interpolation_vars = array();
        $this->interpolation_vars['%PHPCI%'] = 1;
        $this->interpolation_vars['%COMMIT%'] = $build->getCommitId();
        $this->interpolation_vars['%SHORT_COMMIT%'] = substr($build->getCommitId(), 0, 7);
        $this->interpolation_vars['%COMMIT_EMAIL%'] = $build->getCommitterEmail();
        $this->interpolation_vars['%COMMIT_MESSAGE%'] = $build->getCommitMessage();
        $this->interpolation_vars['%COMMIT_URI%'] = $build->getCommitLink();
        $this->interpolation_vars['%BRANCH%'] = $build->getBranch();
        $this->interpolation_vars['%BRANCH_URI%'] = $build->getBranchLink();
        $this->interpolation_vars['%PROJECT%'] = $build->getProjectId();
        $this->interpolation_vars['%BUILD%'] = $build->getId();
        $this->interpolation_vars['%PROJECT_TITLE%'] = $build->getProjectTitle();
        $this->interpolation_vars['%PROJECT_URI%'] = $phpCiUrl . "project/view/" . $build->getProjectId();
        $this->interpolation_vars['%BUILD_PATH%'] = $buildPath;
        $this->interpolation_vars['%BUILD_URI%'] = $phpCiUrl . "build/view/" . $build->getId();
        $this->interpolation_vars['%KIBOKO_CI_APP_COMMIT%'] = $this->interpolation_vars['%COMMIT%'];
        $this->interpolation_vars['%KIBOKO_CI_APP_SHORT_COMMIT%'] = $this->interpolation_vars['%SHORT_COMMIT%'];
        $this->interpolation_vars['%KIBOKO_CI_APP_COMMIT_MESSAGE%'] = $this->interpolation_vars['%COMMIT_MESSAGE%'];
        $this->interpolation_vars['%KIBOKO_CI_APP_COMMIT_EMAIL%'] = $this->interpolation_vars['%COMMIT_EMAIL%'];
        $this->interpolation_vars['%KIBOKO_CI_APP_COMMIT_URI%'] = $this->interpolation_vars['%COMMIT_URI%'];
        $this->interpolation_vars['%KIBOKO_CI_APP_PROJECT%'] = $this->interpolation_vars['%PROJECT%'];
        $this->interpolation_vars['%KIBOKO_CI_APP_BUILD%'] = $this->interpolation_vars['%BUILD%'];
        $this->interpolation_vars['%KIBOKO_CI_APP_PROJECT_TITLE%'] = $this->interpolation_vars['%PROJECT_TITLE%'];
        $this->interpolation_vars['%KIBOKO_CI_APP_PROJECT_URI%'] = $this->interpolation_vars['%PROJECT_URI%'];
        $this->interpolation_vars['%KIBOKO_CI_APP_BUILD_PATH%'] = $this->interpolation_vars['%BUILD_PATH%'];
        $this->interpolation_vars['%KIBOKO_CI_APP_BUILD_URI%'] = $this->interpolation_vars['%BUILD_URI%'];

        putenv('PHPCI=1');
        putenv('KIBOKO_CI_APP_COMMIT=' . $this->interpolation_vars['%COMMIT%']);
        putenv('KIBOKO_CI_APP_SHORT_COMMIT=' . $this->interpolation_vars['%SHORT_COMMIT%']);
        putenv('KIBOKO_CI_APP_COMMIT_MESSAGE=' . $this->interpolation_vars['%COMMIT_MESSAGE%']);
        putenv('KIBOKO_CI_APP_COMMIT_EMAIL=' . $this->interpolation_vars['%COMMIT_EMAIL%']);
        putenv('KIBOKO_CI_APP_COMMIT_URI=' . $this->interpolation_vars['%COMMIT_URI%']);
        putenv('KIBOKO_CI_APP_PROJECT=' . $this->interpolation_vars['%PROJECT%']);
        putenv('KIBOKO_CI_APP_BUILD=' . $this->interpolation_vars['%BUILD%']);
        putenv('KIBOKO_CI_APP_PROJECT_TITLE=' . $this->interpolation_vars['%PROJECT_TITLE%']);
        putenv('KIBOKO_CI_APP_BUILD_PATH=' . $this->interpolation_vars['%BUILD_PATH%']);
        putenv('KIBOKO_CI_APP_BUILD_URI=' . $this->interpolation_vars['%BUILD_URI%']);
    }

    /**
     * Replace every occurrence of the interpolation vars in the given string
     * Example: "This is build %KIBOKO_CI_APP_BUILD%" => "This is build 182"
     * @param string $input
     * @return string
     */
    public function interpolate($input)
    {
        $keys = array_keys($this->interpolation_vars);
        $values = array_values($this->interpolation_vars);
        return str_replace($keys, $values, $input);
    }
}
