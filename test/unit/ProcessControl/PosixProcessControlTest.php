<?php
namespace Tests\Kiboko\Component\ContinuousIntegration\ProcessControl;

use Kiboko\Component\ContinuousIntegration\ProcessControl\PosixProcessControl;

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
