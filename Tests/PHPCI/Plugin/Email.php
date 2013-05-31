<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright	Copyright 2013, Block 8 Limited.
* @license		https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link			http://www.phptesting.org/
*/

namespace PHPCI\Plugin\Tests;
use PHPCI\Plugin\Email as EmailPlugin;

define('PHPCI_BIN_DIR', "FAKEPHPCIBIN");

/**
* Unit test for the PHPUnit plugin.
* @author meadsteve
*/
class EmailTest extends  \PHPUnit_Framework_TestCase
{

	/**
	 * @var EmailPlugin $testedPhpUnit
	 */
	protected $testedEmailPlugin;

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

		$this->loadEmailPluginWithOptions();
	}

	protected function loadEmailPluginWithOptions($arrOptions = array())
	{
		$this->testedEmailPlugin = new EmailPlugin(
            $this->mockCiBuilder,
            $arrOptions
        );
	}

	/**
	 * @covers PHPUnit::execute
	 */
	public function testExecute_ReturnsTrueWithoutArgs()
	{
		$returnValue = $this->testedEmailPlugin->execute();
		$expectedReturn = true;

		$this->assertEquals($expectedReturn, $returnValue);
	}
}