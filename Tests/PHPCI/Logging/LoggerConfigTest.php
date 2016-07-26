<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */
namespace Tests\PHPCI\Plugin\Helper;

use PHPCI\Logging\LoggerConfig;

class LoggerConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFor_ReturnsPSRLogger()
    {
        $config = new LoggerConfig([]);
        $logger = $config->getFor('something');
        $this->assertInstanceOf('\Psr\Log\LoggerInterface', $logger);
    }

    public function testGetFor_ReturnsMonologInstance()
    {
        $config = new LoggerConfig([]);
        $logger = $config->getFor('something');
        $this->assertInstanceOf('\Monolog\Logger', $logger);
    }

    public function testGetFor_AttachesAlwaysPresentHandlers()
    {
        $expectedHandler = new \Monolog\Handler\NullHandler();
        $config          = new LoggerConfig([
            LoggerConfig::KEY_ALWAYS_LOADED => function() use ($expectedHandler) {
                return [$expectedHandler];
            },
        ]);

        /** @type \Monolog\Logger $logger */
        $logger        = $config->getFor('something');
        $actualHandler = $logger->popHandler();

        $this->assertSame($expectedHandler, $actualHandler);
    }

    public function testGetFor_AttachesSpecificHandlers()
    {
        $expectedHandler = new \Monolog\Handler\NullHandler();
        $config          = new LoggerConfig([
            'Specific' => function() use ($expectedHandler) {
                return [$expectedHandler];
            },
        ]);

        /** @type \Monolog\Logger $logger */
        $logger        = $config->getFor('Specific');
        $actualHandler = $logger->popHandler();

        $this->assertSame($expectedHandler, $actualHandler);
    }

    public function testGetFor_IgnoresAlternativeHandlers()
    {
        $expectedHandler    = new \Monolog\Handler\NullHandler();
        $alternativeHandler = new \Monolog\Handler\NullHandler();

        $config = new LoggerConfig([
            'Specific' => function() use ($expectedHandler) {
                return [$expectedHandler];
            },
            'Other' => function() use ($alternativeHandler) {
                return [$alternativeHandler];
            },
        ]);

        /** @type \Monolog\Logger $logger */
        $logger        = $config->getFor('Specific');
        $actualHandler = $logger->popHandler();

        $this->assertSame($expectedHandler, $actualHandler);
        $this->assertNotSame($alternativeHandler, $actualHandler);
    }

    public function testGetFor_SameInstance()
    {
        $config = new LoggerConfig([]);

        $logger1 = $config->getFor('something');
        $logger2 = $config->getFor('something');

        $this->assertSame($logger1, $logger2);
    }
}
