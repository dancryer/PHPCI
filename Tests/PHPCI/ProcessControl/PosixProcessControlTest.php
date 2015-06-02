<?php
namespace Tests\PHPCI\ProcessControl;

use PHPCI\ProcessControl\PosixProcessControl;

class PosixProcessControlTest extends UnixProcessControlTest
{
    protected function setUp()
    {
        $this->object = new PosixProcessControl();
    }

    public function testIsAvailable()
    {
        $this->assertEquals(function_exists('posix_kill'), PosixProcessControl::isAvailable());
    }
}
