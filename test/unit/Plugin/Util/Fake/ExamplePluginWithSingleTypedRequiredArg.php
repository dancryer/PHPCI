<?php

/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\Kiboko\Component\ContinuousIntegration\Plugin\Util\Fake;

use Kiboko\Component\ContinuousIntegration\Plugin;

class ExamplePluginWithSingleTypedRequiredArg implements Plugin
{

    public $RequiredArgument;

    function __construct(\stdClass $requiredArgument)
    {
        $this->RequiredArgument = $requiredArgument;
    }

    public function execute()
    {

    }
}
