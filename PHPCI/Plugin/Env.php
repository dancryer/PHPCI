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
use PHPCI\Helper\BuildInterpolator;
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;

/**
* Environment variable plugin
* @author       Steve Kamerman <stevekamerman@gmail.com>
* @package      PHPCI
* @subpackage   Plugins
*/
class Env implements \PHPCI\Plugin
{
    protected $phpci;
    protected $build;
    protected $env_vars;
    protected $interpolator;

    /**
     * Set up the plugin, configure options, etc.
     *
     * @param Builder $phpci
     * @param Build $build
     * @param BuildInterpolator $interpolator
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, BuildInterpolator $interpolator, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->interpolator = $interpolator;
        $this->env_vars = $options;
    }

    /**
    * Adds the specified environment variables to the builder environment
    */
    public function execute()
    {
        $success = true;
        foreach ($this->env_vars as $key => $value) {
            if (is_numeric($key)) {
                // This allows the developer to specify env vars like " - FOO=bar" or " - FOO: bar"
                if (is_array($value)) {
                    $env_name = key($value);
                    $env_value = current($value);
                } else {
                    list($env_name, $env_value) = explode('=', $value, 2);
                }
            } else {
                // This allows the standard syntax: "FOO: bar"
                $env_name = $key;
                $env_value = $value;
            }

            $interpolated_name = $this->interpolator->interpolate($env_name);
            $interpolated_value = $this->interpolator->interpolate($env_value);

            if (putenv($interpolated_name . "=" . $interpolated_value)) {
                $this->interpolator->setInterpolationVar($interpolated_name, $interpolated_value);
            } else {
                $success = false;
                $this->phpci->logFailure(Lang::get('unable_to_set_env'));
            }
        }
        return $success;
    }
}
