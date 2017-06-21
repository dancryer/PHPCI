<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Helper;

use b8\Config;

/**
* Login Is Disabled Helper - Checks if login is disabled in the view
* @author       Stephen Ball <phpci@stephen.rebelinblue.com>
* @package      Kiboko\Component\ContinuousIntegration
* @subpackage   Web
*/
class LoginIsDisabled
{
    /**
     * Checks if
     * @param $method
     * @param array $params
     * @return mixed|null
     */
    public function __call($method, $params = array())
    {
        unset($method, $params);
        
        $config = Config::getInstance();
        $state = (bool) $config->get('phpci.authentication_settings.state', false);

        return (false !== $state);
    }
}
