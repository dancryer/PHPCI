<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;

/**
* Environment variable plugin
* @author       Steve Kamerman <stevekamerman@gmail.com>
* @package      PHPCI
* @subpackage   Plugins
*/
class Env implements \PHPCI\Plugin
{
    protected $phpci;
    protected $env_vars;

    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $this->phpci    = $phpci;
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
                $env_var = is_array($value)? key($value).'='.current($value): $value;
            } else {
                // This allows the standard syntax: "FOO: bar"
                $env_var = "$key=$value";
            }
            
            if (!putenv($this->phpci->interpolate($env_var))) {
                $success = false;
                $this->phpci->logFailure("Unable to set environment variable");
            }
        }
        return $success;
    }
}
