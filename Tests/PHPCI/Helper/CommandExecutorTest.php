<?php

namespace PHPCI\Plugin\Tests\Helper;

use PHPCI\Helper\CommandExecutor;
use \Prophecy\PhpUnit\ProphecyTestCase;

class CommandExecutorTest extends ProphecyTestCase
{
    /**
     * @var CommandExecutor
     */
    protected $testedExecutor;

    protected function setUp()
    {
        parent::setUp();
        $mockBuildLogger = $this->prophesize('\PHPCI\BuildLogger');
        $this->testedExecutor = new CommandExecutor($mockBuildLogger->reveal());
    }

    public function testGetLastOutput_ReturnsOutputOfCommand()
    {
        $this->testedExecutor->executeCommand(array('echo "%s"', 'Hello World'));
        $output = $this->testedExecutor->getLastOutput();
        $this->assertEquals("Hello World", $output);
    }

    public function testGetLastOutput_ForgetsPreviousCommandOutput()
    {
        $this->testedExecutor->executeCommand(array('echo "%s"', 'Hello World'));
        $this->testedExecutor->executeCommand(array('echo "%s"', 'Hello Tester'));
        $output = $this->testedExecutor->getLastOutput();
        $this->assertEquals("Hello Tester", $output);
    }
}