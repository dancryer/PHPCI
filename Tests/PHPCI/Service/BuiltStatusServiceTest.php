<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         http://www.phptesting.org/
 */

namespace PHPCI\Service\Tests;

use PHPCI\Model\Build;
use PHPCI\Model\Project;
use PHPCI\Service\BuildService;
use PHPCI\Service\BuildStatusService;

/**
 * Unit tests for the ProjectService class.
 * @author Dan Cryer <dan@block8.co.uk>
 */
class BuildStatusServiceTest extends \PHPUnit_Framework_TestCase
{
    const BRANCH = 'master';

    /** @var  Project */
    protected $project;

    protected $timezone;

    public function setUp()
    {
        $project = new Project();
        $project->setId(3);
        $project->setBranch(self::BRANCH);
        $project->setTitle('Test');

        $this->project = $project;
        $this->timezone = date_default_timezone_get();

        date_default_timezone_set('UTC');
    }

    public function tearDown()
    {
        date_default_timezone_set($this->timezone);
    }

    /**
     * @param $configId
     * @param bool $setProject
     * @return Build
     */
    protected function getBuild($configId, $setProject = true)
    {
        $config = array(
            '1' => array(
                'status' => Build::STATUS_RUNNING,
                'id' => 77,
                'finishDateTime' => null,
                'startedDate' => '2014-10-25 21:20:02',
                'previousBuild' => null,
            ),
            '2' => array(
                'status' => Build::STATUS_RUNNING,
                'id' => 78,
                'finishDateTime' => null,
                'startedDate' => '2014-10-25 21:20:02',
                'previousBuild' => 4,
            ),
            '3' => array(
                'status' => Build::STATUS_SUCCESS,
                'id' => 7,
                'finishDateTime' => '2014-10-25 21:50:02',
                'startedDate' => '2014-10-25 21:20:02',
                'previousBuild' => null,
            ),
            '4' => array(
                'status' => Build::STATUS_FAILED,
                'id' => 13,
                'finishDateTime' => '2014-10-13 13:13:13',
                'previousBuild' => null,
            ),
            '5' => array(
                'status' => Build::STATUS_NEW,
                'id' => 1000,
                'finishDateTime' => '2014-12-25 21:12:21',
                'previousBuild' => 3,
            )
        );

        $build = new Build();
        $build->setId($config[$configId]['id']);
        $build->setBranch(self::BRANCH);
        $build->setStatus($config[$configId]['status']);
        if ($config[$configId]['finishDateTime']) {
            $build->setFinished(new \DateTime($config[$configId]['finishDateTime']));
        }
        if (!empty($config[$configId]['startedDate'])) {
            $build->setStarted(new \DateTime('2014-10-25 21:20:02'));
        }

        $project = $this->getProjectMock($config[$configId]['previousBuild'], $setProject);

        $build->setProjectObject($project);

        return $build;
    }

    /**
     * @param null|int $prevBuildId
     * @param bool $setProject
     * @return Project
     */
    protected function getProjectMock($prevBuildId = null, $setProject = true) {

        $project = $this->getMock('PHPCI\Model\Project', array('getLatestBuild'));

        $prevBuild = ($prevBuildId) ? $this->getBuild($prevBuildId, false) : null;

        $project->expects($this->any())
            ->method('getLatestBuild')
            ->will($this->returnValue($prevBuild));

        /* @var $project Project */

        $project->setId(3);
        $project->setBranch(self::BRANCH);
        $project->setTitle('Test');

        if ($setProject) {
            $this->project = $project;
        }

        return $project;

    }

    /**
     * @dataProvider finishedProvider
     *
     * @param int $buildConfigId
     * @param array $expectedResult
     */
    public function testFinished($buildConfigId, array $expectedResult)
    {
        $build = $this->getBuild($buildConfigId);
        $service = new BuildStatusService(self::BRANCH, $this->project, $build);
        $service->setUrl('http://phpci.dev/');
        $this->assertEquals($expectedResult, $service->toArray());
    }

    public function finishedProvider()
    {
        return array(
            'buildingStatus' => array(
                1,
                array(
                    'name' => 'Test / master',
                    'activity' => 'Building',
                    'lastBuildLabel' => '',
                    'lastBuildStatus' => '',
                    'lastBuildTime' => '',
                    'webUrl' => 'http://phpci.dev/build/view/77',
                )
            ),
            'buildingStatusWithPrev' => array(
                2,
                array(
                    'name' => 'Test / master',
                    'activity' => 'Building',
                    'lastBuildLabel' => 13,
                    'lastBuildStatus' => 'Failure',
                    'lastBuildTime' => '2014-10-13T13:13:13+0000',
                    'webUrl' => 'http://phpci.dev/build/view/78',
                )
            ),
            'successStatus' => array(
                3,
                array(
                    'name' => 'Test / master',
                    'activity' => 'Sleeping',
                    'lastBuildLabel' => 7,
                    'lastBuildStatus' => 'Success',
                    'lastBuildTime' => '2014-10-25T21:50:02+0000',
                    'webUrl' => 'http://phpci.dev/build/view/7',
                )
            ),
            'failureStatus' => array(
                4,
                array(
                    'name' => 'Test / master',
                    'activity' => 'Sleeping',
                    'lastBuildLabel' => 13,
                    'lastBuildStatus' => 'Failure',
                    'lastBuildTime' => '2014-10-13T13:13:13+0000',
                    'webUrl' => 'http://phpci.dev/build/view/13',
                )
            ),
            'pending' => array(
                5,
                array(
                    'name' => 'Test / master',
                    'activity' => 'Pending',
                    'lastBuildLabel' => 7,
                    'lastBuildStatus' => 'Success',
                    'lastBuildTime' => '2014-10-25T21:50:02+0000',
                    'webUrl' => 'http://phpci.dev/build/view/1000',
                )
            ),
        );
    }
}