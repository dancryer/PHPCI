<?php

class LoggerConfigTest extends PHPUnit_Framework_TestCase
{
    public function testGetFor_ReturnsPSRLogger()
    {
        $config = new \PHPCI\Helper\LoggerConfig(array());
        $logger = $config->getFor("something");
        $this->assertInstanceOf('\Psr\Log\LoggerInterface', $logger);
    }

    public function testGetFor_ReturnsMonologInstance()
    {
        $config = new \PHPCI\Helper\LoggerConfig(array());
        $logger = $config->getFor("something");
        $this->assertInstanceOf('\Monolog\Logger', $logger);
    }
}
 