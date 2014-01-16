<?php

namespace PHPCI\Plugin\Tests\Helper;

use PHPCI\Logging\BuildLogger;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use Psr\Log\LogLevel;

class BuildLoggerTest extends ProphecyTestCase
{
    /**
     * @var BuildLogger
     */
    protected $testedBuildLogger;

    protected $mockLogger;

    protected $mockBuild;

    protected function setUp()
    {
        parent::setUp();
        $this->mockLogger = $this->prophesize('\Psr\Log\LoggerInterface');
        $this->mockBuild = $this->prophesize('\PHPCI\Model\Build');

        $this->testedBuildLogger = new BuildLogger(
            $this->mockLogger->reveal(),
            $this->mockBuild->reveal()
        );
    }

    public function testLog_CallsWrappedLogger()
    {
        $level = LogLevel::NOTICE;
        $message = "Testing";
        $contextIn = array();

        $this->mockLogger->log($level, $message, Argument::type('array'))
                         ->shouldBeCalledTimes(1);

        $this->testedBuildLogger->log($message, $level, $contextIn);
    }

    public function testLog_CallsWrappedLoggerForEachMessage()
    {
        $level = LogLevel::NOTICE;
        $message = array("One", "Two", "Three");
        $contextIn = array();

        $this->mockLogger->log($level, "One", Argument::type('array'))
                         ->shouldBeCalledTimes(1);

        $this->mockLogger->log($level, "Two", Argument::type('array'))
                         ->shouldBeCalledTimes(1);

        $this->mockLogger->log($level, "Three", Argument::type('array'))
                         ->shouldBeCalledTimes(1);

        $this->testedBuildLogger->log($message, $level, $contextIn);
    }

    public function testLog_AddsBuildToContext()
    {
        $level = LogLevel::NOTICE;
        $message = "Testing";
        $contextIn = array();

        $expectedContext = array(
            'build' => $this->mockBuild->reveal()
        );

        $this->mockLogger->log($level, $message, $expectedContext)
                         ->shouldBeCalledTimes(1);

        $this->testedBuildLogger->log($message, $level, $contextIn);
    }

    public function testLogFailure_LogsAsErrorLevel()
    {
        $message = "Testing";
        $expectedLevel = LogLevel::ERROR;

        $this->mockLogger->log($expectedLevel,
                               Argument::type('string'),
                               Argument::type('array'))
                         ->shouldBeCalledTimes(1);

        $this->testedBuildLogger->logFailure($message);
    }

    public function testLogFailure_AddsExceptionContext()
    {
        $message = "Testing";

        $exception = new \Exception("Expected Exception");


        $this->mockLogger->log(Argument::type('string'),
                               Argument::type('string'),
                               Argument::withEntry('exception', $exception))
                         ->shouldBeCalledTimes(1);

        $this->testedBuildLogger->logFailure($message, $exception);
    }
}
 