<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI;

use PHPCI\Contracts\Plugin as PluginInterface;

/**
 * PHPCI Plugin Interface - Used by all build plugins.
 *
 * This file has been kept maintain backwards compatibility.
 * Plugins should extend \PHPCI\Contracts\Plugin
 *
 * @author Dan Cryer <dan@block8.co.uk>
 */
interface Plugin extends PluginInterface
{
}
