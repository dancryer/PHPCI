<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Controller;

use PHPCI\Controller\BuildStatusController;
use PHPCI\Model\Project;
use b8\Exception\HttpException\NotAuthorizedException;
use Prophecy\Argument;

class BuildStatusControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \b8\Exception\HttpException\NotAuthorizedException
     */
    public function test_ccxml_hidden_for_non_public_project()
    {
        $buildStore = $this->prophesize('PHPCI\Store\BuildStore');
        $projectStore = $this->prophesize('PHPCI\Store\ProjectStore');

        $project = new Project();
        $project->setAllowPublicStatus(false);

        $projectStore->getById(Argument::any())->willReturn($project);

        $webController = new BuildStatusController(
            $this->prophesize('PHPCI\Config')->reveal(),
            $this->prophesize('b8\Http\Request')->reveal(),
            new \b8\Http\Response(),
            $buildStore->reveal(),
            $projectStore->reveal(),
            $this->prophesize('b8\HttpClient')->reveal()
        );

        $result = $webController->handleAction('ccxml', [1]);
    }

    public function test_ccxml_visible_for_public_project()
    {
        $buildStore = $this->prophesize('PHPCI\Store\BuildStore');
        $projectStore = $this->prophesize('PHPCI\Store\ProjectStore');
        $project = new Project();
        $project->setId(1);
        $project->setBranch('test');
        $project->setAllowPublicStatus(true);

        $projectStore->getById(1)->willReturn($project);

        $webController = new BuildStatusController(
            $this->prophesize('PHPCI\Config')->reveal(),
            $this->prophesize('b8\Http\Request')->reveal(),
            new \b8\Http\Response(),
            $buildStore->reveal(),
            $projectStore->reveal(),
            $this->prophesize('b8\HttpClient')->reveal()
        );

        $result = $webController->handleAction('ccxml', [1]);
        $this->assertInstanceOf('b8\Http\Response', $result);

        $responseData = $result->getData();
        $this->assertEquals('text/xml', $responseData['headers']['Content-Type']);
        $this->assertXmlStringEqualsXmlString('<Projects/>', $responseData['body']);
    }


    /**
     * @expectedException \b8\Exception\HttpException\NotAuthorizedException
     */
    public function test_image_hidden_for_non_public_project()
    {
        $buildStore = $this->prophesize('PHPCI\Store\BuildStore');
        $projectStore = $this->prophesize('PHPCI\Store\ProjectStore');

        $project = new Project();
        $project->setAllowPublicStatus(false);

        $projectStore->getById(Argument::any())->willReturn($project);

        $webController = new BuildStatusController(
            $this->prophesize('PHPCI\Config')->reveal(),
            $this->prophesize('b8\Http\Request')->reveal(),
            new \b8\Http\Response(),
            $buildStore->reveal(),
            $projectStore->reveal(),
            $this->prophesize('b8\HttpClient')->reveal()
        );

        $result = $webController->handleAction('image', [1]);
    }

    public function test_image_visible_for_public_project()
    {
        $buildStore = $this->prophesize('PHPCI\Store\BuildStore');
        $projectStore = $this->prophesize('PHPCI\Store\ProjectStore');
        $project = new Project();
        $project->setId(1);
        $project->setBranch('test');
        $project->setAllowPublicStatus(true);

        $projectStore->getById(1)->willReturn($project);

        $shieldsClient = $this->prophesize('b8\HttpClient');
        $shieldsClient->get(Argument::any(), Argument::any())->willReturn(array(
            'body' => '<svg xmlns="http://www.w3.org/2000/svg" width="78" height="18" />',
        ));

        $webController = new BuildStatusController(
            $this->prophesize('PHPCI\Config')->reveal(),
            $this->prophesize('b8\Http\Request')->reveal(),
            new \b8\Http\Response(),
            $buildStore->reveal(),
            $projectStore->reveal(),
            $shieldsClient->reveal()
        );

        $result = $webController->handleAction('image', [1]);
        $this->assertInstanceOf('b8\Http\Response', $result);

        $responseData = $result->getData();
        $this->assertEquals('image/svg+xml', $responseData['headers']['Content-Type']);
        $this->assertXmlStringEqualsXmlString('<svg xmlns="http://www.w3.org/2000/svg" width="78" height="18" />', $responseData['body']);
    }
}
