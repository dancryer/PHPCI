<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration;

use Kiboko\Component\ContinuousIntegration\Model\Build;

/**
 * Kiboko CI Plugin Interface - Used by all build plugins.
 * @author   Dan Cryer <dan@block8.co.uk>
 */
interface ZeroConfigPlugin
{
    public static function canExecute($stage, Builder $builder, Build $build);
}
