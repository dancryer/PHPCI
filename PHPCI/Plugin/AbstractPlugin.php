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
use PHPCI\Model\Build;
use PHPCI\Plugin;

/**
 * Asbtract plugin.
 *
 * Holds helper for the subclasses.
 */
abstract class AbstractPlugin implements Plugin
{
    /**
     * @var Build
     */
    protected $build;

    /**
     * @var Builder
     */
    protected $phpci;

    /**
     * @param Builder $phpci
     * @param Build   $build
     */
    public function __construct(Builder $phpci, Build $build)
    {
        $this->phpci = $phpci;
        $this->build = $build;
    }
}
