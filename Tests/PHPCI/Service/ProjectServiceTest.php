<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license        https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link            http://www.phptesting.org/
 */

namespace PHPCI\Service\Tests;

use PHPCI\Model\Project;
use PHPCI\Service\ProjectService;

/**
 * Unit tests for the ProjectService class.
 * @author Dan Cryer <dan@block8.co.uk>
 */
class ProjectServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ProjectService $testedService
     */
    protected $testedService;

    /**
     * @var \ $mockProjectStore
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

        $this->assertEquals('Test Project', $returnValue->getTitle());
        $this->assertEquals('github', $returnValue->getType());
        $this->assertEquals('block8/phpci', $returnValue->getReference());
        $this->assertEquals('master', $returnValue->getBranch());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_CreateProjectWithOptions()
    {
        $options = array(
            'ssh_private_key' => 'private',
            'ssh_public_key' => 'public',
            'allow_public_status' => 1,
            'build_config' => 'config',
            'branch' => 'testbranch',
        );

        $returnValue = $this->testedService->createProject('Test Project', 'github', 'block8/phpci', $options);

        $this->assertEquals('private', $returnValue->getSshPrivateKey());
        $this->assertEquals('public', $returnValue->getSshPublicKey());
        $this->assertEquals('config', $returnValue->getBuildConfig());
        $this->assertEquals('testbranch', $returnValue->getBranch());
        $this->assertEquals(1, $returnValue->getAllowPublicStatus());
    }

    /**
     * @link https://github.com/Block8/PHPCI/issues/484
     * @covers PHPUnit::execute
     */
    public function testExecute_CreateGitlabProjectWithoutPort()
    {
        $reference = 'git@gitlab.block8.net:block8/phpci.git';
        $returnValue = $this->testedService->createProject('Gitlab', 'gitlab', $reference);

        $this->assertEquals('git', $returnValue->getAccessInformation('user'));
        $this->assertEquals('gitlab.block8.net', $returnValue->getAccessInformation('domain'));
        $this->assertEquals('block8/phpci', $returnValue->getReference());
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

        $this->assertEquals('After Title', $returnValue->getTitle());
        $this->assertEquals('After Reference', $returnValue->getReference());
        $this->assertEquals('bitbucket', $returnValue->getType());
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_EmptyPublicStatus()
    {
        $project = new Project();
        $project->setAllowPublicStatus(1);

        $options = array(
            'ssh_private_key' => 'private',
            'ssh_public_key' => 'public',
            'build_config' => 'config',
        );

        $returnValue = $this->testedService->updateProject($project, 'Test Project', 'github', 'block8/phpci', $options);

        $this->assertEquals(0, $returnValue->getAllowPublicStatus());
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

        $this->assertEquals(true, $service->deleteProject($project));
    }
}
