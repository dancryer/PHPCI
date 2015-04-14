<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Plugin\Helper;

use PHPCI\Helper\UnixCommandExecutor;

class CommandExecutorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UnixCommandExecutor
     */
    protected $testedExecutor;

    protected function setUp()
    {
        if (IS_WIN) {
            $this->markTestSkipped("Cannot test UnixCommandExecutor on ".PHP_OS);
            return;
        }
        parent::setUp();
        $mockBuildLogger = $this->prophesize('PHPCI\Logging\BuildLogger');
        $class = IS_WIN ? 'PHPCI\Helper\WindowsCommandExecutor' : 'PHPCI\Helper\UnixCommandExecutor';
        $this->testedExecutor = new $class($mockBuildLogger->reveal(), __DIR__ . "/");
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

    /**
     * @expectedException \Exception
     * @expectedMessageRegex WorldWidePeace
     */
    public function testFindBinary_ThrowsWhenNotFound()
    {
        $thisFileName = "WorldWidePeace";
        $this->testedExecutor->findBinary($thisFileName);
    }

    public function testFindBinary_ReturnsNullWihQuietArgument()
    {
        $thisFileName = "WorldWidePeace";
        $this->assertNull($this->testedExecutor->findBinary($thisFileName, true));
    }
}
