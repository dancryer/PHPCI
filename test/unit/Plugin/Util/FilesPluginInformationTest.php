<?php

/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\Kiboko\Component\ContinuousIntegration\Plugin\Util;

use Kiboko\Component\ContinuousIntegration\Plugin\Util\FilesPluginInformation;

class FilesPluginInformationTest extends \PHPUnit_Framework_TestCase
{

    public function testGetInstalledPlugins_returnsObjects()
    {
        $pluginDirPath = realpath(__DIR__ . "/../../../../src/Plugin/");
        $test = FilesPluginInformation::newFromDir($pluginDirPath);
        $pluginInfos = $test->getInstalledPlugins();
        $this->assertContainsOnlyInstancesOf('stdClass', $pluginInfos);
    }

    public function testGetPluginClasses_returnsStrings()
    {
        $pluginDirPath = realpath(__DIR__ . "/../../../../src/Plugin/");
        $test = FilesPluginInformation::newFromDir($pluginDirPath);
        $pluginInfos = $test->getPluginClasses();
        $this->assertContainsOnly('string', $pluginInfos);
    }
}

