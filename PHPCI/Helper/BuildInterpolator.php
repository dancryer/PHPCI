<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Helper;

/**
 * The BuildInterpolator class replaces variables in a string with build-specific information.
 *
 * @package PHPCI\Helper
 */
class BuildInterpolator
{
    /**
     *
     * @var Environment
     */
    protected $environment;

    /** Initialize the BuildInterpolator
     *
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Replace every occurrence of the interpolation vars in the given string
     * Example: "This is build %PHPCI_BUILD%" => "This is build 182"
     * @param string $input
     * @return string
     */
    public function interpolate($input)
    {
        $vars = $this->environment->getArrayCopy();
        return preg_replace_callback(
            '/\%(\w+)\%/',
            function ($matches) use ($vars) {
                return isset($vars[$matches[1]]) ? $vars[$matches[1]] : $matches[0];
            },
            $input
        );
    }
}
