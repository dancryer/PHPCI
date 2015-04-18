<?php

namespace Tests\PHPCI\Plugin\Util\Fixtures;

use PHPCI\Plugin;

class ExamplePluginWithSingleOptionalArg implements Plugin
{
    function __construct($optional = null)
    {

    }

    public function execute()
    {

    }
}
