<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license        https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link            http://www.phptesting.org/
 */

namespace PHPCI\Plugin\Tests;

use PHPCI\Plugin\Composer;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Unit test for the Composer plugin.
 *
 * @author Adirelle <adirelle@gmail.com>
 */
class ComposerTest extends ProphecyTestCase
{
    /**
     * @var ObjectProphecy
     */
    private $builder;

    /**
     * @var ObjectProphecy
     */
    private $environment;

    protected function setUp()
    {
        $this->builder = $this->prophesize('\PHPCI\Builder');
        $this->environment = $this->prophesize('\PHPCI\Helper\Environment');

        $this->builder->log(Argument::cetera())->willReturn(null);
        $this->builder->logFailure(Argument::cetera())->willReturn(null);
    }

    public function testExecute_NoComposer()
    {
        $this->builder->findBinary(array('composer', 'composer.phar'))->willReturn(null);

        $plugin = new Composer($this->builder->reveal(), $this->environment->reveal());

        $this->assertFalse($plugin->execute());
    }

    public function testExecute_NoComposerJson()
    {
        $this->builder->findBinary(array('composer', 'composer.phar'))->willReturn('composer');

        $plugin = new Composer($this->builder->reveal(), $this->environment->reveal());

        $this->assertFalse($plugin->execute());
    }

    public function testExecute_Simple()
    {
        $this->builder->findBinary(array('composer', 'composer.phar'))->willReturn('composer');
        $this->builder->reveal()->buildPath = PHPCI_DIR;
        $this->builder->executeCommand(
            'php %s %s --no-interaction %s --working-dir="%s"',
            "composer",
            "install",
            "--prefer-source",
            PHPCI_DIR
        )->willReturn(true);

        $this->environment->addPath(PHPCI_DIR . "vendor" . DIRECTORY_SEPARATOR . "bin")->shouldBeCalled();

        $plugin = new Composer($this->builder->reveal(), $this->environment->reveal());

        $this->assertTrue($plugin->execute());
    }

    public function testExecute_BinDir()
    {
        $this->builder->findBinary(array('composer', 'composer.phar'))->willReturn('composer');

        $fixturesDir = __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures';

        $this->builder->reveal()->buildPath = $fixturesDir;
        $this->builder->executeCommand(
            'php %s %s --no-interaction %s --working-dir="%s"',
            "composer",
            "install",
            "--prefer-source",
            $fixturesDir
        )->willReturn(true);

        $this->environment->addPath($fixturesDir . DIRECTORY_SEPARATOR . ".")->shouldBeCalled();

        $plugin = new Composer($this->builder->reveal(), $this->environment->reveal());

        $this->assertTrue($plugin->execute());
    }

    public function testExecute_PreferDist()
    {
        $this->builder->findBinary(array('composer', 'composer.phar'))->willReturn('composer');
        $this->builder->reveal()->buildPath = PHPCI_DIR;
        $this->builder->executeCommand(
            'php %s %s --no-interaction %s --working-dir="%s"',
            "composer",
            "install",
            "--prefer-dist",
            PHPCI_DIR
        )->willReturn(true);

        $plugin = new Composer($this->builder->reveal(), $this->environment->reveal(), array('prefer_dist' => true));

        $this->assertTrue($plugin->execute());
    }

    public function testExecute_Update()
    {
        $this->builder->findBinary(array('composer', 'composer.phar'))->willReturn('composer');
        $this->builder->reveal()->buildPath = PHPCI_DIR;
        $this->builder->executeCommand(
            'php %s %s --no-interaction %s --working-dir="%s"',
            "composer",
            "update",
            "--prefer-source",
            PHPCI_DIR
        )->willReturn(true);

        $plugin = new Composer($this->builder->reveal(), $this->environment->reveal(), array('action' => 'update'));

        $this->assertTrue($plugin->execute());
    }

    public function testExecute_Directory()
    {
        $this->builder->findBinary(array('composer', 'composer.phar'))->willReturn('composer');
        $this->builder->reveal()->buildPath = __DIR__ . DIRECTORY_SEPARATOR;
        $this->builder->executeCommand(
            'php %s %s --no-interaction %s --working-dir="%s"',
            "composer",
            "install",
            "--prefer-source",
            __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures'
        )->willReturn(true);

        $plugin = new Composer(
            $this->builder->reveal(),
            $this->environment->reveal(),
            array('directory' => 'Fixtures')
        );

        $this->assertTrue($plugin->execute());
    }
}
