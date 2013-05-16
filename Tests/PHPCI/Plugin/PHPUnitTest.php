<?php

namespace PHPCI\Plugin\Tests;

use PHPCI\Plugin\PHPUnit;

define('PHPCI_BIN_DIR', "FAKEPHPCIBIN");

class PHPUnitTest
	extends  \PHPUnit_Framework_TestCase
{

	/**
	 * @var PHPUnit $testedPhpUnit
	 */
	protected $testedPhpUnit;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject $mockCiBuilder
	 */
	protected $mockCiBuilder;

	public function setUp()
	{
		$this->mockCiBuilder = $this->getMock(
			'\PHPCI\Builder',
			array(),
			array(),
			"mockBuilder",
			false
		);
		$this->mockCiBuilder->buildPath = "/";

		$this->loadPhpUnitWithOptions();
	}

	protected function loadPhpUnitWithOptions($arrOptions = array())
	{
		$this->testedPhpUnit = new PHPUnit($this->mockCiBuilder, $arrOptions);
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

		$this->loadPhpUnitWithOptions(array(
			'directory'	=> "Fake/Test/Path"
		));

		$this->mockCiBuilder->expects($this->once())->method("executeCommand");

		$returnValue = $this->testedPhpUnit->execute();
	}

	/**
	 * @covers PHPUnit::execute
	 * @covers PHPUnit::runConfigFile
	 */
	public function testExecute_CallsExecuteCommandOnceWhenGivenStringConfig()
	{
		chdir('/');

		$this->loadPhpUnitWithOptions(array(
			'config'	=> "Fake/Test/config.xml"
		));

		$this->mockCiBuilder->expects($this->once())->method("executeCommand");

		$returnValue = $this->testedPhpUnit->execute();
	}

	/**
	 * @covers PHPUnit::execute
	 * @covers PHPUnit::runDir
	 */
	public function testExecute_CallsExecuteCommandManyTimesWhenGivenArrayDirectory()
	{
		chdir('/');

		$this->loadPhpUnitWithOptions(array(
			'directory'	=> array(0, 1)
		));

		$this->mockCiBuilder->expects($this->at(0))->method("executeCommand");
		$this->mockCiBuilder->expects($this->at(1))->method("executeCommand");

		$returnValue = $this->testedPhpUnit->execute();
	}

	/**
	 * @covers PHPUnit::execute
	 * @covers PHPUnit::runConfigFile
	 */
	public function testExecute_CallsExecuteCommandManyTimesWhenGivenArrayConfig()
	{
		chdir('/');

		$this->loadPhpUnitWithOptions(array(
			'config'	=> array(0, 1)
		));

		$this->mockCiBuilder->expects($this->at(0))->method("executeCommand");
		$this->mockCiBuilder->expects($this->at(1))->method("executeCommand");

		$returnValue = $this->testedPhpUnit->execute();
	}

}