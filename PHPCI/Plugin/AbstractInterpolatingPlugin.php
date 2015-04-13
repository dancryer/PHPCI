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

use PHPCI\Builder;
use PHPCI\Helper\BuildInterpolator;
use PHPCI\Model\Build;
use PHPCI\Plugin\AbstractPlugin;

/**
 * Asbtract plugin which uses a BuildInterpolator.
 */
abstract class AbstractInterpolatingPlugin extends AbstractPlugin
{
    /**
     * @var BuildInterpolator
     */
    protected $interpolator;

    /** Standard constructor.
     *
     * @param Builder $phpci
     * @param Build $build
     * @param BuildInterpolator $interpolator
     */
    public function __construct(Builder $phpci, Build $build, BuildInterpolator $interpolator)
    {
        $this->interpolator = $interpolator;
        parent::__construct($phpci, $build);
    }
}
