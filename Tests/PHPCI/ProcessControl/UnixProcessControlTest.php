<?php
namespace Tests\PHPCI\ProcessControl;

use PHPCI\ProcessControl\UnixProcessControl;

class UnixProcessControlTest extends ProcessControlTest
{
    protected function setUp()
    {
        $this->object = new UnixProcessControl();
    }

    public function getTestCommand()
    {
        return "read SOMETHING";
    }

    public function testIsAvailable()
    {
        $this->assertEquals(DIRECTORY_SEPARATOR === '/', UnixProcessControl::isAvailable());
    }
}
