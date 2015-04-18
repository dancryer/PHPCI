<?php

namespace Tests\PHPCI\Plugin\Util\Fixtures;

use PHPCI\Plugin;

class ExamplePluginWithNoConstructorArgs implements Plugin
{
    public function execute()
    {
    }
}
