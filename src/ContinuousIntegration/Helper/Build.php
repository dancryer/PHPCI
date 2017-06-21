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
class Build
{
    /**
     * Returns a more human-friendly version of a plugin name.
     * @param $name
     * @return mixed
     */
    public function formatPluginName($name)
    {
        return str_replace('Php', 'PHP', ucwords(str_replace('_', ' ', $name)));
    }
}
