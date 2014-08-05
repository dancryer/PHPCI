<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license        https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link            http://www.phptesting.org/
 */

namespace PHPCI\Model\Tests;

use PHPCI\Model\Build;
use PHPCI\Model;

/**
 * Unit tests for the Build model class.
 * @author Dan Cryer <dan@block8.co.uk>
 */
class BuildTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestIsAValidModel()
    {
        $build = new Build();
        $this->assertTrue($build instanceof \b8\Model);
        $this->assertTrue($build instanceof Model);
        $this->assertTrue($build instanceof Model\Base\BuildBase);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestBaseBuildDefaults()
    {
        $build = new Build();
        $this->assertEquals('#', $build->getCommitLink());
        $this->assertEquals('#', $build->getBranchLink());
        $this->assertEquals(null, $build->getFileLinkTemplate());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestIsSuccessful()
    {
        $build = new Build();
        $build->setStatus(Build::STATUS_NEW);
        $this->assertFalse($build->isSuccessful());

        $build->setStatus(Build::STATUS_RUNNING);
        $this->assertFalse($build->isSuccessful());

        $build->setStatus(Build::STATUS_FAILED);
        $this->assertFalse($build->isSuccessful());

        $build->setStatus(Build::STATUS_SUCCESS);
        $this->assertTrue($build->isSuccessful());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestBuildExtra()
    {
        $info = array(
            'item1' => 'Item One',
            'item2' => 2,
        );

        $build = new Build();
        $build->setExtra(json_encode($info));

        $this->assertEquals('Item One', $build->getExtra('item1'));
        $this->assertEquals(2, $build->getExtra('item2'));
        $this->assertNull($build->getExtra('item3'));
        $this->assertEquals($info, $build->getExtra());
    }
}
