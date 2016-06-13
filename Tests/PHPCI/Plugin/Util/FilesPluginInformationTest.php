<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Plugin\Util;

use PHPCI\Plugin\Util\FilesPluginInformation;

class FilesPluginInformationTest extends \PHPUnit_Framework_TestCase
{

    public function testGetInstalledPlugins_returnsObjects()
    {
        $pluginDirPath = realpath(__DIR__ . "/../../../../PHPCI/Plugin/");
        $test = FilesPluginInformation::newFromDir($pluginDirPath);
        $pluginInfos = $test->getInstalledPlugins();
        $this->assertContainsOnlyInstancesOf('stdClass', $pluginInfos);
    }

    public function testGetPluginClasses_returnsStrings()
    {
        $pluginDirPath = realpath(__DIR__ . "/../../../../PHPCI/Plugin/");
        $test = FilesPluginInformation::newFromDir($pluginDirPath);
        $pluginInfos = $test->getPluginClasses();
        $this->assertContainsOnly('string', $pluginInfos);
    }
}

