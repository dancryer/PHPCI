<?php

namespace Tests\PHPCI\Plugin\Util\Fixtures;

use PHPCI\Plugin;

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
