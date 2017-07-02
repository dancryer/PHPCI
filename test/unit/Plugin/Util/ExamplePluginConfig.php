<?php

/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

return function (Kiboko\Component\ContinuousIntegration\Plugin\Util\Factory $factory) {
    $factory->registerResource(
        // This function will be called when the resource is needed.
        function() {
            return array(
                'bar' => "Hello",
            );
        },
        "requiredArgument",
        null
    );
};
