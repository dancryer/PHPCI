<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright Copyright 2015, Block 8 Limited.
 * @license   https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link      http://www.phptesting.org/
 */

namespace PHPCI\Plugin\Tests;

use PHPCI\Plugin\Env;
use Prophecy\PhpUnit\ProphecyTestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Unit test for the Env plugin.
 *
 * @author Adirelle <adirelle@gmail.com>
 */
class EnvTest extends ProphecyTestCase
{
    /**
     * @var ObjectProphecy
     */
    private $interpolator;

    /**
     * @var ObjectProphecy
     */
    private $environment;

    protected function setUp()
    {
        $this->interpolator = $this->prophesize('\PHPCI\Helper\BuildInterpolator');
        $this->environment = $this->prophesize('\PHPCI\Helper\Environment');
    }

    public function testExecute()
    {
        $plugin = new Env(
            $this->interpolator->reveal(),
            $this->environment->reveal(),
            array('FOO' => 'BAR')
        );

        $this->environment->normaliseConfig(array('FOO' => 'BAR'))->willReturn(array('A' => 'B'));

        $this->interpolator->interpolate('A')->willReturn('A');
        $this->interpolator->interpolate('B')->willReturn('C');

        $this->environment->offsetSet('A', 'C')->shouldBeCalled();

        $this->assertTrue($plugin->execute());
    }
}
