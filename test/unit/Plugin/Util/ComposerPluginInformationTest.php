<?php

/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\Kiboko\Component\ContinuousIntegration\Plugin\Util;

use Kiboko\Component\ContinuousIntegration\Plugin\Util\ComposerPluginInformation;

class ComposerPluginInformationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ComposerPluginInformation
     */
    protected $testedInformation;

    protected function setUpFromFile($file)
    {
        $this->testedInformation = ComposerPluginInformation::buildFromYaml($file);
    }

    protected function phpciSetup()
    {
        $this->setUpFromFile(
            __DIR__ . "/../../../../vendor/composer/installed.json"
        );
    }

    public function testBuildFromYaml_ReturnsInstance()
    {
        $this->phpciSetup();
        $this->assertInstanceOf(
            'Kiboko\\Component\\ContinuousIntegration\Plugin\Util\ComposerPluginInformation',
            $this->testedInformation
        );
    }

    public function testGetInstalledPlugins_ReturnsStdClassArray()
    {
        $this->phpciSetup();
        $plugins = $this->testedInformation->getInstalledPlugins();
        $this->assertInternalType("array", $plugins);
        $this->assertContainsOnly("stdClass", $plugins);
    }

    public function testGetPluginClasses_ReturnsStringArray()
    {
        $this->phpciSetup();
        $classes = $this->testedInformation->getPluginClasses();
        $this->assertInternalType("array", $classes);
        $this->assertContainsOnly("string", $classes);
    }
}

