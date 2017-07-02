<?php

/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\Kiboko\Component\ContinuousIntegration\Plugin\Util\Fake;

use Kiboko\Component\ContinuousIntegration\Builder;
use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Build;
use Kiboko\Component\ContinuousIntegration\Plugin;

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
