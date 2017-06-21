<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Helper;

/**
* User Helper - Provides access to logged in user information in views.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class User
{
    /**
     * Proxies method calls through to the current active user model.
     * @param $method
     * @param array $params
     * @return mixed|null
     */
    public function __call($method, $params = array())
    {
        if (empty($_SESSION['phpci_user'])) {
            return null;
        }

        $user = $_SESSION['phpci_user'];

        if (!is_object($user)) {
            return null;
        }

        return call_user_func_array(array($user, $method), $params);
    }
}
