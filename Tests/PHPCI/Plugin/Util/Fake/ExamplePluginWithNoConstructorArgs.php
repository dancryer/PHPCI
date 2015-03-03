<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Plugin\Util\Fake;

use PHPCI\Plugin;

class ExamplePluginWithNoConstructorArgs implements Plugin
{
    public function execute()
    {
    }
}
