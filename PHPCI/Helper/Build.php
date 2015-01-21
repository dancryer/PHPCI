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
