<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */
namespace Tests\PHPCI\Model;

use PHPCI\Model;
use PHPCI\Model\Project;

/**
 * Unit tests for the Project model class.
 *
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

        $this->assertSame('master', $project->getBranch());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestGithubDefaultBranch()
    {
        $project = new Project();
        $project->setType('github');

        $this->assertSame('master', $project->getBranch());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestGitlabDefaultBranch()
    {
        $project = new Project();
        $project->setType('gitlab');

        $this->assertSame('master', $project->getBranch());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestBitbucketDefaultBranch()
    {
        $project = new Project();
        $project->setType('bitbucket');

        $this->assertSame('master', $project->getBranch());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestMercurialDefaultBranch()
    {
        $project = new Project();
        $project->setType('hg');

        $this->assertSame('default', $project->getBranch());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestProjectAccessInformation()
    {
        $info = [
            'item1' => 'Item One',
            'item2' => 2,
        ];

        $project = new Project();
        $project->setAccessInformation($info);

        $this->assertSame('Item One', $project->getAccessInformation('item1'));
        $this->assertSame(2, $project->getAccessInformation('item2'));
        $this->assertNull($project->getAccessInformation('item3'));
        $this->assertSame($info, $project->getAccessInformation());
    }
}
