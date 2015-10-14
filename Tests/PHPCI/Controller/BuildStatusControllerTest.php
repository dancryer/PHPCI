<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Controller;

use PHPCI\Model\Build;

class BuildStatusControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getImageTextAndColorFromStatusDataProvider
     *
     * @param string $status
     * @param string $expectedText
     * @param string $expectedColor
     */
    public function testGetImageTextAndColorFromStatusReturnsTheCorrectTextAndColorForEachStatus(
        $status,
        $expectedText,
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
            $buildStatusControllerReflection->getMethod('getImageTextAndColorFromStatus');
        $getImageColorFromStatusReflection->setAccessible(true);

        list($text, $color) = $getImageColorFromStatusReflection->invoke($buildStatusControllerMock, $status);

        $this->assertEquals($expectedText, $text);
        $this->assertEquals($expectedColor, $color);
    }

    /**
     * @return array
     */
    public function getImageTextAndColorFromStatusDataProvider()
    {
        return array(
            array(Build::STATUS_NEW, 'pending', 'blue'),
            array(Build::STATUS_RUNNING, 'running', 'yellow'),
            array(Build::STATUS_SUCCESS, 'success', 'green'),
            array(Build::STATUS_FAILED, 'failed', 'red'),
        );
    }
}
