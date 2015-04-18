<?php

namespace Tests\PHPCI\Plugin\Util\Fixtures;

use PHPCI\Plugin;

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
