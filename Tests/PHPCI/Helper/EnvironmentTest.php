<?php

namespace PHPCI\Plugin\Tests\Helper;

use PHPCI\Helper\Environment;

class EnvironmentTest extends \Prophecy\PhpUnit\ProphecyTestCase
{
    public function testDefaultConstructor()
    {
        $env = new Environment();

        $this->assertEquals("1", $env['PHPCI']);
        $this->assertEquals(getenv('PATH'), $env['PATH']);
    }

    public function testGetPaths_Simple()
    {
        $env = new Environment(array('PATH' => implode(PATH_SEPARATOR, array('a', 'b'))));

        $this->assertEquals(array('a', 'b'), $env->getPaths());
    }

    public function testGetPaths_NoPath()
    {
        $env = new Environment(array());

        $this->assertEquals(array(), $env->getPaths());
    }

    public function testAddPath_NoPath()
    {
        $env = new Environment(array());

        $env->addPath('A');
        $env->addPath('B');

        $this->assertEquals('A' . PATH_SEPARATOR . 'B', $env['PATH']);
    }

    public function testAddPath_Default()
    {
        $env = new Environment(array('PATH' => 'A'));

        $env->addPath('B');

        $this->assertEquals('A' . PATH_SEPARATOR . 'B', $env['PATH']);
    }

    public function testAddPath_Before()
    {
        $env = new Environment(array());

        $env->addPath('A');
        $env->addPath('B');
        $env->addPath('C', 'B');

        $this->assertEquals('A' . PATH_SEPARATOR . 'C' . PATH_SEPARATOR . 'B', $env['PATH']);
    }

    public function testAddPath_BeforeNotFound()
    {
        $env = new Environment(array());

        $env->addPath('A');
        $env->addPath('B');
        $env->addPath('C', 'D');

        $this->assertEquals('A' . PATH_SEPARATOR . 'B' . PATH_SEPARATOR . 'C', $env['PATH']);
    }

    public function testAddBuildVariables()
    {
        if (!defined('PHPCI_URL')) {
            define('PHPCI_URL', 'http://foo.bar/');
        }

        $build = $this->prophesize('PHPCI\Model\Build');
        $build->getId()->willReturn(5);
        $build->getCommitId()->willReturn('0123456789');
        $build->getCommitLink()->willReturn(PHPCI_URL . 'commits/0123456789');
        $build->getCommitterEmail()->willReturn('john.doe@nowhere.com');
        $build->getBranch()->willReturn('HEAD');
        $build->getBranchLink()->willReturn(PHPCI_URL. 'branch/HEAD');
        $build->getProjectId()->willReturn(7);
        $build->getProjectTitle()->willReturn("The Project");

        $env = new Environment(array('PATH' => '/usr/bin'));

        $env->addBuildVariables($build->reveal(), 'FOO');

        $this->assertEquals(
            array(
                'PATH' => '/usr/bin'. PATH_SEPARATOR . 'FOO',
                'PHPCI' => '1',

                'BRANCH' => 'HEAD',
                'BRANCH_URI' => PHPCI_URL . 'branch/HEAD',
                'BUILD' => 5,
                'BUILD_PATH' => 'FOO',
                'BUILD_URI' => PHPCI_URL . 'build/view/5',
                'COMMIT' => '0123456789',
                'COMMIT_EMAIL' => 'john.doe@nowhere.com',
                'COMMIT_URI' => PHPCI_URL . 'commits/0123456789',
                'PROJECT' => 7,
                'PROJECT_TITLE' => 'The Project',
                'PROJECT_URI' => PHPCI_URL . 'project/view/7',
                'SHORT_COMMIT' => '0123456',

                'PHPCI_BRANCH' => 'HEAD',
                'PHPCI_BRANCH_URI' => PHPCI_URL . 'branch/HEAD',
                'PHPCI_BUILD' => 5,
                'PHPCI_BUILD_PATH' => 'FOO',
                'PHPCI_BUILD_URI' => PHPCI_URL . 'build/view/5',
                'PHPCI_COMMIT' => '0123456789',
                'PHPCI_COMMIT_EMAIL' => 'john.doe@nowhere.com',
                'PHPCI_COMMIT_URI' => PHPCI_URL . 'commits/0123456789',
                'PHPCI_PROJECT' => 7,
                'PHPCI_PROJECT_TITLE' => 'The Project',
                'PHPCI_PROJECT_URI' => PHPCI_URL . 'project/view/7',
                'PHPCI_SHORT_COMMIT' => '0123456',

                'TERM' => 'ansi-generic',
                'LANG' => 'C'
            ),
            $env->getArrayCopy()
        );
    }

    public function testNormaliseConfig()
    {
        $env = new Environment(array());

        $this->assertEquals(
            array(
                'FOO' => 'BAR',
                'JOHN' => 'DOE',
                'HOMER' => 'SIMPSON',
                'MARGE' => 'BOUVIER',
            ),
            $env->normaliseConfig(
                array(
                    'FOO=BAR',
                    'JOHN' => 'DOE',
                    array(
                        'HOMER' => 'SIMPSON',
                        'MARGE=BOUVIER'
                    )
                )
            )
        );
    }
}
