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

    protected function setUp()
    {
        parent::setup();
        $this->testedInterpolator = new BuildInterpolator();
    }

    public function testInterpolate_LeavesStringsUnchangedByDefault()
    {
        $string = "Hello World";
        $expectedOutput = "Hello World";

        $actualOutput = $this->testedInterpolator->interpolate($string);

        $this->assertEquals($expectedOutput, $actualOutput);
    }

    public function testInterpolate_LeavesStringsUnchangedWhenBuildIsSet()
    {
        $build = $this->prophesize('PHPCI\\Model\\Build')->reveal();

        $string = "Hello World";
        $expectedOutput = "Hello World";

        $this->testedInterpolator->setupInterpolationVars(
            $build,
            "/buildpath/",
            "phpci.com"
        );

        $actualOutput = $this->testedInterpolator->interpolate($string);

        $this->assertEquals($expectedOutput, $actualOutput);
    }
}
 