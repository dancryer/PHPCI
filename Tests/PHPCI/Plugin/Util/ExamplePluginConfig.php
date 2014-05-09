<?php
return function (PHPCI\Plugin\Util\Factory $factory) {
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