<?php
namespace Tests\Kiboko\Component\ContinuousIntegration\ProcessControl;

use Kiboko\Component\ContinuousIntegration\ProcessControl\WindowsProcessControl;

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
