<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license        https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link            http://www.phptesting.org/
 */

namespace PHPCI\Model\Tests;

use PHPCI\Model\Project;
use PHPCI\Model;

/**
 * Unit tests for the Project model class.
 * @author Dan Cryer <dan@block8.co.uk>
 */
class ProjectTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestIsAValidModel()
    {
        $project = new Project();
        $this->assertTrue($project instanceof \b8\Model);
        $this->assertTrue($project instanceof Model);
        $this->assertTrue($project instanceof Model\Base\ProjectBase);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestGitDefaultBranch()
    {
        $project = new Project();
        $project->setType('git');

        $this->assertEquals('master', $project->getBranch());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestGithubDefaultBranch()
    {
        $project = new Project();
        $project->setType('github');

        $this->assertEquals('master', $project->getBranch());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestGitlabDefaultBranch()
    {
        $project = new Project();
        $project->setType('gitlab');

        $this->assertEquals('master', $project->getBranch());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestBitbucketDefaultBranch()
    {
        $project = new Project();
        $project->setType('bitbucket');

        $this->assertEquals('master', $project->getBranch());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestMercurialDefaultBranch()
    {
        $project = new Project();
        $project->setType('hg');

        $this->assertEquals('default', $project->getBranch());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestProjectAccessInformation()
    {
        $info = array(
            'item1' => 'Item One',
            'item2' => 2,
        );

        $project = new Project();
        $project->setAccessInformation($info);

        $this->assertEquals('Item One', $project->getAccessInformation('item1'));
        $this->assertEquals(2, $project->getAccessInformation('item2'));
        $this->assertNull($project->getAccessInformation('item3'));
        $this->assertEquals($info, $project->getAccessInformation());
    }
}
