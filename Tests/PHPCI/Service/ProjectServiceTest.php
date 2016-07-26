<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */
namespace Tests\PHPCI\Service;

use PHPCI\Model\Project;
use PHPCI\Service\ProjectService;

/**
 * Unit tests for the ProjectService class.
 *
 * @author Dan Cryer <dan@block8.co.uk>
 */
class ProjectServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @type ProjectService $testedService
     */
    protected $testedService;

    /**
     * @type \ $mockProjectStore
     */
    protected $mockProjectStore;

    public function setUp()
    {
        $this->mockProjectStore = $this->getMock('PHPCI\Store\ProjectStore');
        $this->mockProjectStore->expects($this->any())
                               ->method('save')
                               ->will($this->returnArgument(0));

        $this->testedService = new ProjectService($this->mockProjectStore);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_CreateBasicProject()
    {
        $returnValue = $this->testedService->createProject('Test Project', 'github', 'block8/phpci');

        $this->assertSame('Test Project', $returnValue->getTitle());
        $this->assertSame('github', $returnValue->getType());
        $this->assertSame('block8/phpci', $returnValue->getReference());
        $this->assertSame('master', $returnValue->getBranch());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_CreateProjectWithOptions()
    {
        $options = [
            'ssh_private_key'     => 'private',
            'ssh_public_key'      => 'public',
            'allow_public_status' => 1,
            'build_config'        => 'config',
            'branch'              => 'testbranch',
        ];

        $returnValue = $this->testedService->createProject('Test Project', 'github', 'block8/phpci', $options);

        $this->assertSame('private', $returnValue->getSshPrivateKey());
        $this->assertSame('public', $returnValue->getSshPublicKey());
        $this->assertSame('config', $returnValue->getBuildConfig());
        $this->assertSame('testbranch', $returnValue->getBranch());
        $this->assertSame(1, $returnValue->getAllowPublicStatus());
    }

    /**
     * @link https://github.com/Block8/PHPCI/issues/484
     * @covers PHPUnit::execute
     */
    public function testExecute_CreateGitlabProjectWithoutPort()
    {
        $reference   = 'git@gitlab.block8.net:block8/phpci.git';
        $returnValue = $this->testedService->createProject('Gitlab', 'gitlab', $reference);

        $this->assertSame('git', $returnValue->getAccessInformation('user'));
        $this->assertSame('gitlab.block8.net', $returnValue->getAccessInformation('domain'));
        $this->assertSame('block8/phpci', $returnValue->getReference());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_UpdateExistingProject()
    {
        $project = new Project();
        $project->setTitle('Before Title');
        $project->setReference('Before Reference');
        $project->setType('github');

        $returnValue = $this->testedService->updateProject($project, 'After Title', 'bitbucket', 'After Reference');

        $this->assertSame('After Title', $returnValue->getTitle());
        $this->assertSame('After Reference', $returnValue->getReference());
        $this->assertSame('bitbucket', $returnValue->getType());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_EmptyPublicStatus()
    {
        $project = new Project();
        $project->setAllowPublicStatus(1);

        $options = [
            'ssh_private_key' => 'private',
            'ssh_public_key'  => 'public',
            'build_config'    => 'config',
        ];

        $returnValue = $this->testedService->updateProject($project, 'Test Project', 'github', 'block8/phpci', $options);

        $this->assertSame(0, $returnValue->getAllowPublicStatus());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_DeleteProject()
    {
        $store = $this->getMock('PHPCI\Store\ProjectStore');
        $store->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(true));

        $service = new ProjectService($store);
        $project = new Project();

        $this->assertSame(true, $service->deleteProject($project));
    }
}
