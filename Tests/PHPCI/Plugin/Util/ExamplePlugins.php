<?php
namespace PHPCI\Plugin\Tests\Util;

use PHPCI\Builder;
use PHPCI\Model\Build;
use PHPCI\Plugin;

class ExamplePluginWithNoConstructorArgs {

}

class ExamplePluginWithSingleOptionalArg {
    function __construct($optional = null)
    {

    }
}

class ExamplePluginWithSingleRequiredArg {

    public $RequiredArgument;

    function __construct($requiredArgument)
    {
        $this->RequiredArgument = $requiredArgument;
    }
}

class ExamplePluginWithSingleTypedRequiredArg {

    public $RequiredArgument;

    function __construct(\stdClass $requiredArgument)
    {
        $this->RequiredArgument = $requiredArgument;
    }
}

class ExamplePluginFull implements Plugin {

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