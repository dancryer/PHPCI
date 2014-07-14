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

/**
 * Unit tests for the ProjectService class.
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
}
