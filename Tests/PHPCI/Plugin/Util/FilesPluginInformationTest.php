<?php

namespace PHPCI\Plugin\Tests\Util;

use PHPCI\Plugin\Util\FilesPluginInformation;

class FilesPluginInformationTest extends \PHPUnit_Framework_TestCase
{

    public function testGetInstalledPlugins_returnsObjectes()
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
 