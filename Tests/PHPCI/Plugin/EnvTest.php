<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2013, Block 8 Limited.
 * @license        https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link            http://www.phptesting.org/
 */

namespace PHPCI\Plugin\Tests;

use PHPCI\Plugin\Env as EnvPlugin;

/**
 * Unit test for the PHPUnit plugin.
 * @author adirelle@gmail.cmo
 */
class EnvTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EnvPlugin
     */
    protected $testedEnvPlugin;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockBuild;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockBuilder;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockInterpolator;

    /**
     * Environment array to be used by the putenv() mock.
     *
     * @var array
     */
    public static $env;

    protected function setUp()
    {
        self::$env = array();

        $this->mockBuilder = $this->getMockBuilder('PHPCI\Builder')->disableOriginalConstructor()->getMock();

        $this->mockBuilder->expects($this->never())->method('logFailure');

        $this->mockBuild = $this->getMock('PHPCI\Model\Build');

        $this->mockInterpolator = $this->getMock('PHPCI\Helper\BuildInterpolator');

        $this->mockInterpolator
            ->expects($this->any())
            ->method('interpolate')
            ->willReturnCallback(function($value) { return "X$value"; });

        $this->testedEnvPlugin = new EnvPlugin(
            $this->mockBuilder,
            $this->mockBuild,
            $this->mockInterpolator,
            array(
                'FOO=BAR',
                "FOO2" => "BAR2",
                array("FOO3" => "BAR3")
            )
        );
    }

    public function testExecute_PutEnv()
    {
        $this->mockInterpolator
            ->expects($this->exactly(6))
            ->method('interpolate')
            ->withConsecutive(['FOO'], ['BAR'], ['FOO2'], ['BAR2'], ['FOO3'], ['BAR3']);

        $this->assertTrue($this->testedEnvPlugin->execute());

        $this->assertEquals("XBAR", self::$env["XFOO"]);
        $this->assertEquals("XBAR2", self::$env["XFOO2"]);
        $this->assertEquals("XBAR3", self::$env["XFOO3"]);
    }

    public function testExecute_SetInterpolationVar()
    {
        $this->mockInterpolator
            ->expects($this->exactly(3))
            ->method('setInterpolationVar')
            ->withConsecutive(['XFOO', 'XBAR'], ['XFOO2', 'XBAR2'], ['XFOO3', 'XBAR3']);

        $this->assertTrue($this->testedEnvPlugin->execute());
    }

}

// Create a "putenv" function in the PHPCI\Plugin namespace.
namespace PHPCI\Plugin;

/**
 * Mock the global function "putenv".
 *
 * @param string $var
 * @return boolean
 */
function putenv($var)
{
    list($name, $value) = explode("=", $var, 2);
    \PHPCI\Plugin\Tests\EnvTest::$env[$name] = $value;
    return true;
}
