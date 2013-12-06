<?php

namespace PHPCI\Plugin\Tests\Util;

require_once __DIR__ . "/ExamplePlugins.php";

use PHPCI\Plugin\Util\Executor;
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
        $this->mockBuildLogger = $this->prophesize('\PHPCI\BuildLogger');
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
        $pluginNamespace = 'PHPCI\\Plugin\\';

        $mockPlugin = $this->prophesize('PHPCI\Plugin');
        $mockPlugin->execute()->shouldBeCalledTimes(1);

        $this->mockFactory->buildPlugin($pluginNamespace . $pluginName, $options)
          ->shouldBeCalledTimes(1)
          ->willReturn($mockPlugin->reveal());

        $this->testedExecutor->executePlugin($pluginName, $options);
    }

    public function testExecutePlugin_ReturnsPluginSuccess()
    {
        $options = array();
        $pluginName = 'PhpUnit';
        $pluginNamespace = 'PHPCI\\Plugin\\';

        $expectedReturnValue = true;

        $mockPlugin = $this->prophesize('PHPCI\Plugin');
        $mockPlugin->execute()->willReturn($expectedReturnValue);

        $this->mockFactory->buildPlugin($pluginNamespace . $pluginName, $options)->willReturn($mockPlugin->reveal());

        $returnValue = $this->testedExecutor->executePlugin($pluginName, $options);

        $this->assertEquals($expectedReturnValue, $returnValue);
    }

}
 