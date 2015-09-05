<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Controller;

class BuildStatusControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getImageColorFromStatusDataProvider
     *
     * @param string $status
     * @param string $expectedColor
     */
    public function testGetImageColorFromStatusReturnsTheCorrectColorForEachStatus(
        $status,
        $expectedColor
    ) {
        $buildStatusControllerMock =
            $this->getMockBuilder('PHPCI\Controller\BuildStatusController')
                 ->disableOriginalConstructor()
                 ->setMethods(null)
                 ->getMock();

        $buildStatusControllerReflection =
            new \ReflectionClass('PHPCI\Controller\BuildStatusController');

        $getImageColorFromStatusReflection =
            $buildStatusControllerReflection->getMethod('getImageColorFromStatus');
        $getImageColorFromStatusReflection->setAccessible(true);

        $this->assertEquals(
            $expectedColor,
            $getImageColorFromStatusReflection->invoke($buildStatusControllerMock, $status)
        );
    }

    public function getImageColorFromStatusDataProvider()
    {
        return array(
            array('passing', 'green'),
            array('running', 'orange'),
            array('failed', 'red'),
            array('error', 'red'),
            array('unknown', 'lightgrey'),
            array('test the default case', 'lightgrey'),
        );
    }
}
