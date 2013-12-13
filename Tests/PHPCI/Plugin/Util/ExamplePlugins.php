<?php
namespace PHPCI\Plugin\Tests\Util;

use PHPCI\Builder;
use PHPCI\Model\Build;
use PHPCI\Plugin;

class ExamplePluginWithNoConstructorArgs implements Plugin
{
    public function execute()
    {
    }
}

class ExamplePluginWithSingleOptionalArg implements Plugin
{
    function __construct($optional = null)
    {

    }

    public function execute()
    {

    }
}

class ExamplePluginWithSingleRequiredArg implements Plugin
{

    public $RequiredArgument;

    function __construct($requiredArgument)
    {
        $this->RequiredArgument = $requiredArgument;
    }

    public function execute()
    {

    }
}

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