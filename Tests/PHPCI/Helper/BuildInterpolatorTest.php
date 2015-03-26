<?php

namespace PHPCI\Plugin\Tests\Helper;

use PHPCI\Helper\BuildInterpolator;
use Prophecy\PhpUnit\ProphecyTestCase;

class BuildInterpolatorTest extends ProphecyTestCase
{
    /**
     * @var BuildInterpolator
     */
    protected $testedInterpolator;

    /**
     *
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    protected $mockEnv;

    protected function setUp()
    {
        parent::setup();

        $this->mockEnv = $this->prophesize('\PHPCI\Helper\Environment');
        $this->testedInterpolator = new BuildInterpolator($this->mockEnv->reveal());
    }

    public function testInterpolate_LeavesStringsUnchangedByDefault()
    {
        $this->mockEnv->getArrayCopy()->willReturn(array('FOO' => 'BAR'));

        $string = "Hello World";
        $expectedOutput = "Hello World";

        $actualOutput = $this->testedInterpolator->interpolate($string);

        $this->assertEquals($expectedOutput, $actualOutput);
    }

    public function testInterpolate_ReplaceStrings()
    {
        $this->mockEnv->getArrayCopy()->willReturn(array('FOO' => 'BAR'));

        $this->assertEquals("AABARBB", $this->testedInterpolator->interpolate('AA%FOO%BB'));
    }

    public function testInterpolate_NotRecursive()
    {
        $this->mockEnv->getArrayCopy()->willReturn(array('FOO' => 'C%BAR%D', 'BAR' => 'EE'));

        $this->assertEquals("AAC%BAR%DBB", $this->testedInterpolator->interpolate('AA%FOO%BB'));
    }

    public function testInterpolate_OrderDoesNotMatter()
    {
        $this->mockEnv->getArrayCopy()->willReturn(array('BAR' => 'EE', 'FOO' => 'C%BAR%D'));

        $this->assertEquals("AAC%BAR%DBB", $this->testedInterpolator->interpolate('AA%FOO%BB'));
    }

}
