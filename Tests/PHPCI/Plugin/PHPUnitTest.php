<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2013, Block 8 Limited.
 * @license        https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link            http://www.phptesting.org/
 */

namespace PHPCI\Plugin\Tests;

use PHPCI\Plugin\PhpUnit;

/**
 * Unit test for the PHPUnit plugin.
 * @author meadsteve
 */
class PHPUnitTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PhpUnit $testedPhpUnit
     */
    protected $testedPhpUnit;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $mockCiBuilder
     */
    protected $mockCiBuilder;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $mockCiBuilder
     */
    protected $mockBuild;

    public function setUp()
    {
        $this->mockCiBuilder = $this->getMock(
            '\PHPCI\Builder',
            array('findBinary', 'executeCommand'),
            array(),
            "mockBuilder_phpUnit",
            false
        );
        $this->mockCiBuilder->buildPath = "/";

        $this->mockBuild = $this->getMock(
            '\PHPCI\Model\Build',
            array(),
            array(),
            "MockBuild",
            false
        );

        $this->loadPhpUnitWithOptions();
    }

    protected function loadPhpUnitWithOptions($arrOptions = array())
    {
        $this->testedPhpUnit = new PhpUnit($this->mockCiBuilder, $this->mockBuild, $arrOptions);
    }

    /**
     * @param \PHPUnit_Framework_MockObject_Matcher_Invocation $expectation
     */
    protected function expectFindBinaryToBeCalled($expectation)
    {
        $this->mockCiBuilder->expects($expectation)
            ->method("findBinary")
            ->will($this->returnValue("phpunit"));
    }

    /**
     * @param \PHPUnit_Framework_MockObject_Matcher_Invocation $expectation
     */
    public function expectExectuteCommandToBeCalled($expectation)
    {
        $this->mockCiBuilder->expects($expectation)
            ->method("executeCommand");
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_ReturnsTrueWithoutArgs()
    {
        $returnValue = $this->testedPhpUnit->execute();
        $expectedReturn = true;

        $this->assertEquals($expectedReturn, $returnValue);
    }

    /**
     * @covers PHPUnit::execute
     * @covers PHPUnit::runDir
     */
    public function testExecute_CallsExecuteCommandOnceWhenGivenStringDirectory()
    {
        chdir('/');

        $this->loadPhpUnitWithOptions(
            array(
                'directory' => "Fake/Test/Path"
            )
        );

        $this->expectFindBinaryToBeCalled($this->once());
        $this->expectExectuteCommandToBeCalled($this->once());

        $returnValue = $this->testedPhpUnit->execute();
    }

    /**
     * @covers PHPUnit::execute
     * @covers PHPUnit::runConfigFile
     */
    public function testExecute_CallsExecuteCommandOnceWhenGivenStringConfig()
    {
        chdir('/');

        $this->loadPhpUnitWithOptions(
            array(
                'config' => "Fake/Test/config.xml"
            )
        );

        $this->expectFindBinaryToBeCalled($this->once());
        $this->expectExectuteCommandToBeCalled($this->once());

        $returnValue = $this->testedPhpUnit->execute();
    }

    /**
     * @covers PHPUnit::execute
     * @covers PHPUnit::runDir
     */
    public function testExecute_CallsExecuteCommandManyTimesWhenGivenArrayDirectory()
    {
        chdir('/');

        $this->loadPhpUnitWithOptions(
            array(
                'directory' => array("dir1", "dir2")
            )
        );

        $this->expectFindBinaryToBeCalled($this->exactly(2));
        $this->expectExectuteCommandToBeCalled($this->exactly(2));

        $returnValue = $this->testedPhpUnit->execute();
    }

    /**
     * @covers PHPUnit::execute
     * @covers PHPUnit::runConfigFile
     */
    public function testExecute_CallsExecuteCommandManyTimesWhenGivenArrayConfig()
    {
        chdir('/');

        $this->loadPhpUnitWithOptions(
            array(
                'config' => array("configfile1.xml", "configfile2.xml")
            )
        );

        $this->expectFindBinaryToBeCalled($this->exactly(2));
        $this->expectExectuteCommandToBeCalled($this->exactly(2));

        $returnValue = $this->testedPhpUnit->execute();
    }
}