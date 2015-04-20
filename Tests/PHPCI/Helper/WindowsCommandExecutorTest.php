<?php

namespace PHPCI\Plugin\Tests\Helper;

use PHPCI\Helper\Environment;
use PHPCI\Helper\WindowsCommandExecutor;
use Prophecy\PhpUnit\ProphecyTestCase;

class WindowsCommandExecutorTest extends ProphecyTestCase
{
    /**
     * @var WindowsCommandExecutor
     */
    protected $testedExecutor;

    protected function setUp()
    {
        if (DIRECTORY_SEPARATOR !== '\\') {
            $this->markTestSkipped("Cannot test WindowsCommandExecutor on " . PHP_OS);
        }
        parent::setUp();

        $mockBuildLogger = $this->prophesize('PHPCI\Logging\BuildLogger');
        $this->testedExecutor = new WindowsCommandExecutor($mockBuildLogger->reveal(), __DIR__ . DIRECTORY_SEPARATOR);
    }

    public function testGetLastOutput_ReturnsOutputOfCommand()
    {
        $this->testedExecutor->executeCommand(array('echo "%s"', 'Hello World'));
        $output = $this->testedExecutor->getLastOutput();
        $this->assertEquals('"Hello World"', $output);
    }

    public function testGetLastOutput_ForgetsPreviousCommandOutput()
    {
        $this->testedExecutor->executeCommand(array('echo "%s"', 'Hello World'));
        $this->testedExecutor->executeCommand(array('echo "%s"', 'Hello Tester'));
        $output = $this->testedExecutor->getLastOutput();
        $this->assertEquals('"Hello Tester"', $output);
    }

    public function testExecuteCommand_ReturnsTrueForValidCommands()
    {
        $returnValue = $this->testedExecutor->executeCommand(array('echo "%s"', 'Hello World'));
        $this->assertTrue($returnValue);
    }

    public function testExecuteCommand_ReturnsFalseForInvalidCommands()
    {
        $returnValue = $this->testedExecutor->executeCommand(array('eerfdcvcho "%s"', 'Hello World'));
        $this->assertFalse($returnValue);
    }

    public function testExecuteCommand_Environment()
    {
        $mockBuildLogger = $this->prophesize('PHPCI\Logging\BuildLogger');

        $environment = new Environment();

        $this->testedExecutor = new WindowsCommandExecutor(
            $mockBuildLogger->reveal(), __DIR__ . DIRECTORY_SEPARATOR,
            $environment
        );

        $environment['FOO'] = 'BAR';

        $this->assertTrue($this->testedExecutor->executeCommand(array('echo %FOO%')));
        $this->assertEquals('BAR', $this->testedExecutor->getLastOutput());
    }

    public function testExecuteCommand_Script()
    {
        $mockBuildLogger = $this->prophesize('PHPCI\Logging\BuildLogger');

        $environment = new Environment();

        $this->testedExecutor = new WindowsCommandExecutor(
            $mockBuildLogger->reveal(), __DIR__ . DIRECTORY_SEPARATOR,
            $environment
        );

        $environment['FOO'] = 'BAR';
        $fixtureDir = __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures';
        $environment->addPath($fixtureDir);

        $this->assertTrue($this->testedExecutor->executeCommand(array('phpci_test_batch')));
        $this->assertEquals('BAR', $this->testedExecutor->getLastOutput());
    }

    public function testFindBinary_ReturnsPathInSpecifiedRoot()
    {
        $mockBuildLogger = $this->prophesize('PHPCI\Logging\BuildLogger');
        $mockEnvironement = $this->prophesize('PHPCI\Logging\BuildLogger');
        $this->testedExecutor = new WindowsCommandExecutor($mockBuildLogger->reveal(), __DIR__ . DIRECTORY_SEPARATOR);

        $thisFileName = basename(__FILE__);
        $returnValue = $this->testedExecutor->findBinary($thisFileName);
        $this->assertEquals(__FILE__, $returnValue);
    }

    public function testFindBinary_ReturnsPathInEnvironmentPath()
    {
        $mockBuildLogger = $this->prophesize('PHPCI\Logging\BuildLogger');

        $environment = new Environment();

        $this->testedExecutor = new WindowsCommandExecutor(
            $mockBuildLogger->reveal(), __DIR__ . DIRECTORY_SEPARATOR,
            $environment
        );

        $fixtureDir = __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures';
        $environment->addPath($fixtureDir);

        $this->assertEquals(
            $fixtureDir . DIRECTORY_SEPARATOR . 'phpci_test_batch.bat',
            $this->testedExecutor->findBinary('phpci_test_batch')
        );
    }

}
