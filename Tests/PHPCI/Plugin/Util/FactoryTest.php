<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Plugin\Util;

use PHPCI\Plugin\Util\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \PHPCI\Plugin\Util\Factory
     */
    protected $testedFactory;

    protected $expectedResource;

    protected $resourceLoader;

    protected function setUp()
    {
        $this->testedFactory = new Factory();

        // Setup a resource that can be returned and asserted against
        $this->expectedResource = new \stdClass();
        $resourceLink = $this->expectedResource;
        $this->resourceLoader = function() use (&$resourceLink) {
            return $resourceLink;
        };
    }

    protected function tearDown()
    {
        // Nothing to do.
    }


    public function testRegisterResourceThrowsExceptionWithoutTypeAndName()
    {
        $this->setExpectedException('InvalidArgumentException', 'Type or Name must be specified');
        $this->testedFactory->registerResource($this->resourceLoader, null, null);
    }

    public function testRegisterResourceThrowsExceptionIfLoaderIsntFunction()
    {
        $this->setExpectedException('InvalidArgumentException', '$loader is expected to be a function');
        $this->testedFactory->registerResource(array("dummy"), "TestName", "TestClass");
    }

    public function testBuildPluginWorksWithConstructorlessPlugins()
    {
        $pluginClass = $this->getFakePluginClassName('ExamplePluginWithNoConstructorArgs');
        $plugin = $this->testedFactory->buildPlugin($pluginClass);
        $this->assertInstanceOf($pluginClass, $plugin);
    }

    public function testBuildPluginFailsForNonPluginClasses()
    {
        $this->setExpectedException('InvalidArgumentException', 'Requested class must implement \PHPCI\Plugin');
        $plugin = $this->testedFactory->buildPlugin("stdClass");
    }

    public function testBuildPluginWorksWithSingleOptionalArgConstructor()
    {
        $pluginClass = $this->getFakePluginClassName('ExamplePluginWithSingleOptionalArg');
        $plugin = $this->testedFactory->buildPlugin($pluginClass);
        $this->assertInstanceOf($pluginClass, $plugin);
    }

    public function testBuildPluginThrowsExceptionIfMissingResourcesForRequiredArg()
    {
        $this->setExpectedException(
            'DomainException',
            'Unsatisfied dependency: requiredArgument'
        );

        $pluginClass = $this->getFakePluginClassName('ExamplePluginWithSingleRequiredArg');
        $plugin = $this->testedFactory->buildPlugin($pluginClass);
    }

    public function testBuildPluginLoadsArgumentsBasedOnName()
    {
        $pluginClass = $this->getFakePluginClassName('ExamplePluginWithSingleRequiredArg');

        $this->testedFactory->registerResource(
            $this->resourceLoader,
            "requiredArgument"
        );

        /** @var ExamplePluginWithSingleRequiredArg $plugin */
        $plugin = $this->testedFactory->buildPlugin($pluginClass);

        $this->assertEquals($this->expectedResource, $plugin->RequiredArgument);
    }

    public function testBuildPluginLoadsArgumentsBasedOnType()
    {
        $pluginClass = $this->getFakePluginClassName('ExamplePluginWithSingleTypedRequiredArg');

        $this->testedFactory->registerResource(
            $this->resourceLoader,
            null,
            "stdClass"
        );

        /** @var ExamplePluginWithSingleTypedRequiredArg $plugin */
        $plugin = $this->testedFactory->buildPlugin($pluginClass);

        $this->assertEquals($this->expectedResource, $plugin->RequiredArgument);
    }

    public function testBuildPluginLoadsFullExample()
    {
        $pluginClass = $this->getFakePluginClassName('ExamplePluginFull');

        $this->registerBuildAndBuilder();

        /** @var ExamplePluginFull $plugin */
        $plugin = $this->testedFactory->buildPlugin($pluginClass);

        $this->assertInstanceOf($pluginClass, $plugin);
    }

    public function testBuildPluginLoadsFullExampleWithOptions()
    {
        $pluginClass = $this->getFakePluginClassName('ExamplePluginFull');

        $expectedArgs = array(
            'thing' => "stuff"
        );

        $this->registerBuildAndBuilder();

        /** @var ExamplePluginFull $plugin */
        $plugin = $this->testedFactory->buildPlugin(
            $pluginClass,
            $expectedArgs
        );

        $this->assertInternalType('array', $plugin->Options);
        $this->assertArrayHasKey('thing', $plugin->Options);
    }

    public function testAddConfigFromFile_ReturnsTrueForValidFile()
    {
        $result = $this->testedFactory->addConfigFromFile(
            realpath(__DIR__ . "/ExamplePluginConfig.php")
        );

        $this->assertTrue($result);
    }

    public function testAddConfigFromFile_RegistersResources()
    {
        $this->testedFactory->addConfigFromFile(
            realpath(__DIR__ . "/ExamplePluginConfig.php")
        );

        $pluginClass = $this->getFakePluginClassName('ExamplePluginWithSingleRequiredArg');
        $plugin = $this->testedFactory->buildPlugin($pluginClass);

        // The Example config file defines an array as the resource.
        $this->assertEquals(
            array("bar" => "Hello"),
            $plugin->RequiredArgument
        );
    }

    /**
     * Registers mocked Builder and Build classes so that realistic plugins
     * can be tested.
     */
    private function registerBuildAndBuilder()
    {
        $self = $this;

        $this->testedFactory->registerResource(
            function () use ($self) {
                return $self->getMock(
                    'PHPCI\Builder',
                    array(),
                    array(),
                    '',
                    false
                );
            },
            null,
            'PHPCI\\Builder'
        );

        $this->testedFactory->registerResource(
            function () use ($self) {
                return $self->getMock(
                    'PHPCI\Model\Build',
                    array(),
                    array(),
                    '',
                    false
                );
            },
            null,
            'PHPCI\\Model\\Build'
        );
    }

    protected function getFakePluginClassName($pluginName)
    {
        $pluginNamespace = '\\Tests\\PHPCI\\Plugin\\Util\\Fake\\';

        return $pluginNamespace . $pluginName;
    }
}

