<?php

/**
 * PHPCI - Continuous Integration for PHP.
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Helper\BuildInterpolator;
use PHPCI\Plugin\Util\InterpolatorAwareInterface;

/**
 * Asbtract plugin which uses a BuildInterpolator.
 */
abstract class AbstractInterpolatingPlugin extends AbstractExecutingPlugin implements InterpolatorAwareInterface
{
    /**
     * @var BuildInterpolator
     */
    protected $interpolator;

    /**
     *
     * @param BuildInterpolator $interpolator
     */
    public function setInterpolator(BuildInterpolator $interpolator)
    {
        $this->interpolator = $interpolator;
    }
}
