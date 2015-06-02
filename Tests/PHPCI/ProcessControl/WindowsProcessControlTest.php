<?php
namespace Tests\PHPCI\ProcessControl;

use PHPCI\ProcessControl\WindowsProcessControl;

class WindowsProcessControlTest extends ProcessControlTest
{
    protected function setUp()
    {
        $this->object = new WindowsProcessControl;
    }

    public function getTestCommand()
    {
        return "pause";
    }

    public function testIsAvailable()
    {
        $this->assertEquals(DIRECTORY_SEPARATOR === '\\', WindowsProcessControl::isAvailable());
    }
}
