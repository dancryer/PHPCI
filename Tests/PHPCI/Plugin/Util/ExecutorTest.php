<?php

namespace PHPCI\Plugin\Tests\Util;

require_once __DIR__ . "/ExamplePlugins.php";

use PHPCI\Plugin\Util\Executor;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;

class ExecutorTest extends ProphecyTestCase
{
    /**
     * @var Executor
     */
    protected $testedExecutor;

    protected $mockBuildLogger;

    protected $mockFactory;

    protected function setUp()
    {
        parent::setUp();
        $this->mockBuildLogger = $this->prophesize('\PHPCI\Logging\BuildLogger');
        $this->mockFactory = $this->prophesize('\PHPCI\Plugin\Util\Factory');
        $this->testedExecutor = new Executor($this->mockFactory->reveal(), $this->mockBuildLogger->reveal());
    }

    public function testExecutePlugin_AssumesPHPCINamespaceIfNoneGiven()
    {
        $options = array();
        $pluginName = 'PhpUnit';
        $pluginNamespace = 'PHPCI\\Plugin\\';

        $this->mockFactory->buildPlugin($pluginNamespace . $pluginName, $options)
                          ->shouldBeCalledTimes(1)
                          ->willReturn($this->prophesize('PHPCI\Plugin')->reveal());

        $this->testedExecutor->executePlugin($pluginName, $options);
    }

    public function testExecutePlugin_KeepsCalledNameSpace()
    {
        $options = array();
        $pluginName = 'ExamplePluginFull';
        $pluginNamespace = '\\PHPCI\\Plugin\\Tests\\Util\\';

        $this->mockFactory->buildPlugin($pluginNamespace . $pluginName, $options)
          ->shouldBeCalledTimes(1)
          ->willReturn($this->prophesize('PHPCI\Plugin')->reveal());

        $this->testedExecutor->executePlugin($pluginNamespace . $pluginName, $options);
    }

    public function testExecutePlugin_CallsExecuteOnFactoryBuildPlugin()
    {
        $options = array();
        $pluginName = 'PhpUnit';

        $mockPlugin = $this->prophesize('PHPCI\Plugin');
        $mockPlugin->execute()->shouldBeCalledTimes(1);

        $this->mockFactory->buildPlugin(Argument::any(), Argument::any())->willReturn($mockPlugin->reveal());

        $this->testedExecutor->executePlugin($pluginName, $options);
    }

    public function testExecutePlugin_ReturnsPluginSuccess()
    {
        $options = array();
        $pluginName = 'PhpUnit';

        $expectedReturnValue = true;

        $mockPlugin = $this->prophesize('PHPCI\Plugin');
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

        $mockPlugin = $this->prophesize('PHPCI\Plugin');
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

        $config = array(
           'stageOne' => array(
               'PhpUnit' => $phpUnitPluginOptions,
               'Behat' => $behatPluginOptions,
           )
        );

        $pluginNamespace = 'PHPCI\\Plugin\\';

        $mockPhpUnitPlugin = $this->prophesize('PHPCI\Plugin');
        $mockPhpUnitPlugin->execute()->shouldBeCalledTimes(1)->willReturn(true);

        $this->mockFactory->buildPlugin($pluginNamespace . 'PhpUnit', $phpUnitPluginOptions)
                          ->willReturn($mockPhpUnitPlugin->reveal());


        $mockBehatPlugin = $this->prophesize('PHPCI\Plugin');
        $mockBehatPlugin->execute()->shouldBeCalledTimes(1)->willReturn(true);

        $this->mockFactory->buildPlugin($pluginNamespace . 'Behat', $behatPluginOptions)
                          ->willReturn($mockBehatPlugin->reveal());


        $this->testedExecutor->executePlugins($config, 'stageOne');
    }

}
 