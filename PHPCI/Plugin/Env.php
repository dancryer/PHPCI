<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014-2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Helper\BuildInterpolator;
use PHPCI\Helper\Environment;
use PHPCI\Plugin;

/**
 * Environment variable plugin
 *
 * @author       Steve Kamerman <stevekamerman@gmail.com>
 * @author       Adirelle <adirelle@gmail.com>
 * @package      PHPCI\Plugin
 */
class Env implements Plugin
{
    /**
     * @var BuildInterpolator
     */
    protected $interpolator;

    /**
     * @var Environment
     */
    protected $environment;

    /**
     * @var array
     */
    protected $options;

    /**
     * Set up the plugin, configure options, etc.
     *
     * @param Environment $environment
     * @param array $options
     */
    public function __construct(BuildInterpolator $interpolator, Environment $environment, array $options = array())
    {
        $this->interpolator = $interpolator;
        $this->environment = $environment;
        $this->options = $options;
    }

    /**
     * Adds the specified environment variables to the builder environment
     */
    public function execute()
    {
        $vars = $this->environment->normaliseConfig($this->options);

        foreach ($vars as $name => $value) {
            $interpolatedName = $this->interpolator->interpolate($name);
            $interpolatedValue = $this->interpolator->interpolate($value);
            $this->environment[$interpolatedName] = $interpolatedValue;
        }

        return true;
    }
}
