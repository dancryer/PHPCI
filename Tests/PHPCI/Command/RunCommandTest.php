<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         http://www.phptesting.org/
 */

namespace PHPCI\Command\Tests;

use DateTime;
use Exception;
use PHPCI\Command\RunCommand;
use PHPCI\Model\Build;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use Prophecy\Prophecy\ObjectProphecy;

class RunCommandTest extends ProphecyTestCase
{
    /**
     * @var RunCommand
     */
    private $command;

    /**
     * @var ObjectProphecy
     */
    private $logger;

    /**
     * @var ObjectProphecy
     */
    private $store;

    /**
     * @var ObjectProphecy
     */
    private $factory;

    protected function setUp()
    {
        $this->logger = $this->prophesize('\Monolog\Logger');
        $this->store = $this->prophesize('\PHPCI\Store\BuildStore');
        $this->factory = $this->prophesize('\PHPCI\BuilderFactory');

        $this->command = new RunCommand($this->logger->reveal(), $this->store->reveal(), $this->factory->reveal(), 100);
    }

    public function testRunBuild_SuccessFul()
    {
        $project = $this->prophesize('\PHPCI\Model\Project');
        $project->getType()->willReturn('test');

        $build = $this->prophesize('\PHPCI\Model\Build');
        $build->getProject()->willReturn($project);
        $build->getId()->willReturn(1);
        $build->setFinished(Argument::type('\DateTime'))->shouldBeCalled();

        $builder = $this->prophesize('\PHPCI\Builder');
        $builder->execute()->shouldBeCalled();
        $builder->removeBuildDirectory()->shouldBeCalled();

        $this->factory->createBuilder($build)->willReturn($builder);

        $this->store->save($build)->shouldBeCalled();

        $this->command->runBuild($build->reveal());
    }

    public function testRunBuild_Exception()
    {
        $project = $this->prophesize('\PHPCI\Model\Project');
        $project->getType()->willReturn('test');

        $build = $this->prophesize('\PHPCI\Model\Build');
        $build->getProject()->willReturn($project);
        $build->getId()->willReturn(1);
        $build->setStatus(Build::STATUS_FAILED)->shouldBeCalled();
        $build->getLog()->willReturn("FOO");
        $build->setLog("FOO" . PHP_EOL . PHP_EOL . "BAR")->shouldBeCalled();
        $build->setFinished(Argument::type('\DateTime'))->shouldBeCalled();

        $builder = $this->prophesize('\PHPCI\Builder');
        $builder->execute()->willThrow(new Exception("BAR"));
        $builder->removeBuildDirectory()->shouldBeCalled();

        $this->factory->createBuilder($build)->willReturn($builder);

        $this->store->save($build)->shouldBeCalled();

        $this->command->runBuild($build->reveal());
    }

    public function testValidateRunningBuilds_NoRunningBuilds()
    {
        $this->store->getByStatus(Build::STATUS_RUNNING)->willReturn(array('items' => array()));

        $this->assertEquals(array(), $this->command->validateRunningBuilds());
    }

    public function testValidateRunningBuilds_OneActiveBuild()
    {
        $build = $this->prophesize('\PHPCI\Model\Build');
        $build->getProjectId()->willReturn(1);
        $build->getStarted()->willReturn(new DateTime());

        $this->store->getByStatus(Build::STATUS_RUNNING)->willReturn(array('items' => array($build)));

        $this->assertEquals(array(1 => true), $this->command->validateRunningBuilds());
    }


    public function testValidateRunningBuilds_SeveralActiveBuild()
    {
        $build1 = $this->prophesize('\PHPCI\Model\Build');
        $build1->getProjectId()->willReturn(1);
        $build1->getStarted()->willReturn(new DateTime());

        $build2 = $this->prophesize('\PHPCI\Model\Build');
        $build2->getProjectId()->willReturn(2);
        $build2->getStarted()->willReturn(new DateTime());

        $build3 = $this->prophesize('\PHPCI\Model\Build');
        $build3->getProjectId()->willReturn(2);
        $build3->getStarted()->willReturn(new DateTime());

        $items = array($build1->reveal(), $build2->reveal(), $build3->reveal());

        $this->store->getByStatus(Build::STATUS_RUNNING)->willReturn(array('items' => $items));

        $this->assertEquals(array(1 => true, 2 => true), $this->command->validateRunningBuilds());
    }

    public function testValidateRunningBuilds_StaleBuild()
    {
        $build = $this->prophesize('\PHPCI\Model\Build');
        $build->getId()->willReturn(1);
        $build->getStarted()->willReturn(new DateTime('now - 1 day'));
        $build->setStatus(Build::STATUS_FAILED)->shouldBeCalled();
        $build->setFinished(Argument::type('\DateTime'))->shouldBeCalled();

        $this->store->getByStatus(Build::STATUS_RUNNING)->willReturn(array('items' => array($build)));
        $this->store->save($build)->shouldBeCalled();

        $this->assertEquals(array(), $this->command->validateRunningBuilds());
    }

    /**
     * @depends testValidateRunningBuilds_OneActiveBuild
     */
    public function testFindNextPendingBuild_NoPendingBuilds()
    {
        // Fetch testValidateRunningBuilds_OneActiveBuild setup
        $this->testValidateRunningBuilds_OneActiveBuild();

        $this->store->getByStatus(Build::STATUS_NEW)->willReturn(array('items' => array()));

        $this->assertNull($this->command->findNextPendingBuild());
    }

    /**
     * @depends testValidateRunningBuilds_NoRunningBuilds
     */
    public function testFindNextPendingBuild_OnePendingBuild()
    {
        // Fetch testValidateRunningBuilds_NoRunningBuilds setup
        $this->testValidateRunningBuilds_NoRunningBuilds();

        $build = $this->prophesize('\PHPCI\Model\Build');
        $build->getProjectId()->willReturn(2);

        $this->store->getByStatus(Build::STATUS_NEW)->willReturn(array('items' => array($build)));

        $this->assertSame($build->reveal(), $this->command->findNextPendingBuild());
    }

    /**
     * @depends testValidateRunningBuilds_NoRunningBuilds
     */
    public function testFindNextPendingBuild_SeveralPendingBuilds()
    {
        // Fetch testValidateRunningBuilds_NoRunningBuilds setup
        $this->testValidateRunningBuilds_NoRunningBuilds();

        $build1 = $this->prophesize('\PHPCI\Model\Build');
        $build1->getProjectId()->willReturn(1);

        $build2 = $this->prophesize('\PHPCI\Model\Build');
        $build2->getProjectId()->willReturn(2);

        $this->store->getByStatus(Build::STATUS_NEW)->willReturn(array('items' => array($build1, $build2)));

        $this->assertSame($build1->reveal(), $this->command->findNextPendingBuild());
    }

    /**
     * @depends testValidateRunningBuilds_OneActiveBuild
     */
    public function testFindNextPendingBuild_SameProjectBuild()
    {
        // Fetch testValidateRunningBuilds_OneActiveBuild setup
        $this->testValidateRunningBuilds_OneActiveBuild();

        $build1 = $this->prophesize('\PHPCI\Model\Build');
        $build1->getId()->willReturn(1);
        $build1->getProjectId()->willReturn(1);

        $build2 = $this->prophesize('\PHPCI\Model\Build');
        $build2->getProjectId()->willReturn(2);

        $this->store->getByStatus(Build::STATUS_NEW)->willReturn(array('items' => array($build1, $build2)));

        $this->assertSame($build2->reveal(), $this->command->findNextPendingBuild());
    }
}
