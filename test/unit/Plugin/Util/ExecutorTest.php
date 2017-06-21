<?php

/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\Kiboko\Component\ContinuousIntegration\Plugin\Util;

use Kiboko\Component\ContinuousIntegration\Plugin\Util\Executor;
use Prophecy\Argument;

class ExecutorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Executor
     */
    protected $testedExecutor;

    protected $mockBuildLogger;

    protected $mockFactory;

    protected $mockStore;

    protected function setUp()
    {
        parent::setUp();
        $this->mockBuildLogger = $this->prophesize('Kiboko\\Component\\ContinuousIntegration\Logging\BuildLogger');
        $this->mockFactory = $this->prophesize('Kiboko\\Component\\ContinuousIntegration\Plugin\Util\Factory');
        $this->mockStore = $this->prophesize('Kiboko\\Component\\ContinuousIntegration\Store\BuildStore');
        $this->testedExecutor = new Executor(
            $this->mockFactory->reveal(),
            $this->mockBuildLogger->reveal(),
            $this->mockStore->reveal()
        );
    }

    public function testExecutePlugin_AssumesCINamespaceIfNoneGiven()
    {
        $options = array();
        $pluginName = 'PhpUnit';
        $pluginNamespace = 'Kiboko\\Component\\ContinuousIntegration\\Plugin\\';

        $this->mockFactory->buildPlugin($pluginNamespace . $pluginName, $options)
                          ->shouldBeCalledTimes(1)
                          ->willReturn($this->prophesize('Kiboko\\Component\\ContinuousIntegration\Plugin')->reveal());

        $this->testedExecutor->executePlugin($pluginName, $options);
    }

    public function testExecutePlugin_KeepsCalledNameSpace()
    {
        $options = array();
        $pluginClass = $this->getFakePluginClassName('ExamplePluginFull');

        $this->mockFactory->buildPlugin($pluginClass, $options)
          ->shouldBeCalledTimes(1)
          ->willReturn($this->prophesize('Kiboko\\Component\\ContinuousIntegration\Plugin')->reveal());

        $this->testedExecutor->executePlugin($pluginClass, $options);
    }

    public function testExecutePlugin_CallsExecuteOnFactoryBuildPlugin()
    {
        $options = array();
        $pluginName = 'PhpUnit';
        $build = new \Kiboko\Component\ContinuousIntegration\Model\Build();

        $mockPlugin = $this->prophesize('Kiboko\\Component\\ContinuousIntegration\Plugin');
        $mockPlugin->execute()->shouldBeCalledTimes(1);

        $this->mockFactory->buildPlugin(Argument::any(), Argument::any())->willReturn($mockPlugin->reveal());
        $this->mockFactory->getResourceFor('Kiboko\\Component\\ContinuousIntegration\Model\Build')->willReturn($build);

        $this->testedExecutor->executePlugin($pluginName, $options);
    }

    public function testExecutePlugin_ReturnsPluginSuccess()
    {
        $options = array();
        $pluginName = 'PhpUnit';

        $expectedReturnValue = true;

        $mockPlugin = $this->prophesize('Kiboko\\Component\\ContinuousIntegration\Plugin');
        $mockPlugin->execute()->willReturn($expectedReturnValue);

        $this->mockFactory->buildPlugin(Argument::any(), Argument::any())->willReturn($mockPlugin->reveal());

        $returnValue = $this->testedExecutor->executePlugin($pluginName, $options);

        $this->assertEquals($expectedReturnValue, $returnValue);
    }

    public function testExecutePlugin_LogsFailureForNonExistentClasses()
    {
        $options = array();
        $pluginName = 'DOESNTEXIST';

        $this->mockBuildLogger->logFailure('Plugin does not exist: ' . $pluginName)->shouldBeCalledTimes(1);

        $this->testedExecutor->executePlugin($pluginName, $options);
    }

    public function testExecutePlugin_LogsFailureWhenExceptionsAreThrownByPlugin()
    {
        $options = array();
        $pluginName = 'PhpUnit';

        $expectedException = new \RuntimeException("Generic Error");

        $mockPlugin = $this->prophesize('Kiboko\\Component\\ContinuousIntegration\Plugin');
        $mockPlugin->execute()->willThrow($expectedException);

        $this->mockFactory->buildPlugin(Argument::any(), Argument::any())->willReturn($mockPlugin->reveal());

        $this->mockBuildLogger->logFailure('Exception: ' . $expectedException->getMessage(), $expectedException)
                              ->shouldBeCalledTimes(1);

        $this->testedExecutor->executePlugin($pluginName, $options);
    }

    public function testExecutePlugins_CallsEachPluginForStage()
    {
        $phpUnitPluginOptions = array();
        $behatPluginOptions = array();
        $build = new \Kiboko\Component\ContinuousIntegration\Model\Build();

        $config = array(
           'stageOne' => array(
               'PhpUnit' => $phpUnitPluginOptions,
               'Behat' => $behatPluginOptions,
           )
        );

        $pluginNamespace = 'Kiboko\\Component\\ContinuousIntegration\\Plugin\\';

        $mockPhpUnitPlugin = $this->prophesize('Kiboko\\Component\\ContinuousIntegration\Plugin');
        $mockPhpUnitPlugin->execute()->shouldBeCalledTimes(1)->willReturn(true);

        $this->mockFactory->buildPlugin($pluginNamespace . 'PhpUnit', $phpUnitPluginOptions)
                          ->willReturn($mockPhpUnitPlugin->reveal());
        $this->mockFactory->getResourceFor('Kiboko\\Component\\ContinuousIntegration\Model\Build')->willReturn($build);

        $mockBehatPlugin = $this->prophesize('Kiboko\\Component\\ContinuousIntegration\Plugin');
        $mockBehatPlugin->execute()->shouldBeCalledTimes(1)->willReturn(true);

        $this->mockFactory->buildPlugin($pluginNamespace . 'Behat', $behatPluginOptions)
                          ->willReturn($mockBehatPlugin->reveal());

        $this->testedExecutor->executePlugins($config, 'stageOne');
    }

    protected function getFakePluginClassName($pluginName)
    {
        $pluginNamespace = 'Tests\\Kiboko\\Component\\ContinuousIntegration\\Plugin\\Util\\Fake\\';

        return $pluginNamespace . $pluginName;
    }
}

