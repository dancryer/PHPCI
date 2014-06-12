<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI;

use PHPCI\Model\Build;

/**
 * PHPCI Plugin Interface - Used by all build plugins.
 * @author   Dan Cryer <dan@block8.co.uk>
 */
interface ZeroConfigPlugin
{
    public static function canExecute($stage, Builder $builder, Build $build);
}
