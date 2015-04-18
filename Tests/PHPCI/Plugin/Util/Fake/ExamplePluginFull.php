<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Plugin\Util\Fake;

use PHPCI\Builder;
use PHPCI\Model\Build;
use PHPCI\Plugin;

class ExamplePluginFull implements Plugin {
    /**
     * @var array
     */
    public $Options;

    public function __construct(
        Builder $phpci,
        Build $build,
        array $options = array()
    )
    {
        $this->Options = $options;
    }

    public function execute()
    {

    }
}
