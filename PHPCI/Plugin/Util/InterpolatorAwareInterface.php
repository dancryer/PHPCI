<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin\Util;

use PHPCI\Helper\BuildInterpolator;

/**
 * An object that accepts a build interpolator.
 *
 * @author   Adirelle <adirelle@gmail.com>
 */
interface InterpolatorAwareInterface
{
    /**
     * Sets the BuildInterpolator.
     *
     * @param BuildInterpolator $interpolator
     */
    public function setInterpolator(BuildInterpolator $interpolator);
}
