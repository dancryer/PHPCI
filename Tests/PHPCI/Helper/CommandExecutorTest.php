<?php

namespace PHPCI\Plugin\Tests\Helper;

use PHPCI\Helper\UnixCommandExecutor;
use \Prophecy\PhpUnit\ProphecyTestCase;

class CommandExecutorTest extends ProphecyTestCase
{
    /**
     * @var UnixCommandExecutor
     */
    protected $testedExecutor;

    protected function setUp()
    {
        parent::setUp();
        $mockBuildLogger = $this->prophesize('PHPCI\Logging\BuildLogger');
        $this->testedExecutor = new UnixCommandExecutor($mockBuildLogger->reveal(), __DIR__ . "/");
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

    public function testExecuteCommand_ReturnsTrueForValidCommands()
    {
        $returnValue = $this->testedExecutor->executeCommand(array('echo "%s"', 'Hello World'));
        $this->assertTrue($returnValue);
    }

    public function testExecuteCommand_ReturnsFalseForInvalidCommands()
    {
        $returnValue = $this->testedExecutor->executeCommand(array('eerfdcvcho "%s" > /dev/null 2>&1', 'Hello World'));
        $this->assertFalse($returnValue);
    }

    public function testFindBinary_ReturnsPathInSpecifiedRoot()
    {
        $thisFileName = "CommandExecutorTest.php";
        $returnValue = $this->testedExecutor->findBinary($thisFileName);
        $this->assertEquals(__DIR__ . "/" . $thisFileName, $returnValue);
    }
}