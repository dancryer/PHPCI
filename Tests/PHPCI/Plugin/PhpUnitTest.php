<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2013, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Plugin;

/**
 * Unit test for the PHPUnit plugin.
 *
 * @author Pablo Tejada <pablo@ptejada.com>
 */
class PhpUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleConfigFile()
    {
        $options = array(
            'config' => PHPCI_DIR . 'phpunit.xml'
        );

        $mockPlugin = $this->getPluginBuilder($options)->setMethods(array('runConfigFile'))->getMock();
        $mockPlugin->expects($this->once())->method('runConfigFile')->with(PHPCI_DIR . 'phpunit.xml');

        $mockPlugin->execute();
    }

    public function testMultiConfigFile()
    {
        $options = array(
            'config' => array(
                PHPCI_DIR . 'phpunit1.xml',
                PHPCI_DIR . 'phpunit2.xml',
            )
        );

        $mockPlugin = $this->getPluginBuilder($options)->setMethods(array('runConfigFile'))->getMock();
        $mockPlugin->expects($this->exactly(2))->method('runConfigFile')->withConsecutive(
            array(PHPCI_DIR . 'phpunit1.xml'), array(PHPCI_DIR . 'phpunit2.xml')
        );

        $mockPlugin->execute();
    }



    /**
     * @param array $options
     *
     * @return \PHPUnit_Framework_MockObject_MockBuilder
     */
    protected function getPluginBuilder($options = array())
    {
        $loggerMock = $this->getMockBuilder('\Monolog\Logger')
            ->setConstructorArgs(array('Test'))
            ->setMethods(array('addRecord'))
            ->getMock();

        $mockBuild   = $this->getMockBuilder('\PHPCI\Model\Build')->getMock();
        $mockBuilder = $this->getMockBuilder('\PHPCI\Builder')
            ->setConstructorArgs(array($mockBuild, $loggerMock))
            ->setMethods(array('executeCommand'))->getMock();

        return $this->getMockBuilder('PHPCI\Plugin\PhpUnitV2')->setConstructorArgs(
            array($mockBuilder, $mockBuild, $options)
        );
    }

    public function testSingleDir()
    {
        $options = array(
            'directory' => '/test/directory/one'
        );

        $mockPlugin = $this->getPluginBuilder($options)->setMethods(array('runDir'))->getMock();
        $mockPlugin->expects($this->once())->method('runDir')->with('/test/directory/one');

        $mockPlugin->execute();
    }

    public function testMultiDir()
    {
        $options = array(
            'directory' => array(
                '/test/directory/one',
                '/test/directory/two',
            )
        );

        $mockPlugin = $this->getPluginBuilder($options)->setMethods(array('runDir'))->getMock();
        $mockPlugin->expects($this->exactly(2))->method('runDir')->withConsecutive(
            array('/test/directory/one'), array('/test/directory/two')
        );

        $mockPlugin->execute();
    }

    public function testProcessResultsFromConfig()
    {
        $options = array(
            'config' => PHPCI_DIR . 'phpunit.xml'
        );

        $mockPlugin = $this->getPluginBuilder($options)->setMethods(array('processResults'))->getMock();
        $mockPlugin->expects($this->once())->method('processResults')->with($this->isType('string'));

        $mockPlugin->execute();
    }

    public function testProcessResultsFromDir()
    {
        $options = array(
            'directory' => PHPCI_DIR . 'Tests'
        );

        $mockPlugin = $this->getPluginBuilder($options)->setMethods(array('processResults'))->getMock();
        $mockPlugin->expects($this->once())->method('processResults')->with($this->isType('string'));

        $mockPlugin->execute();
    }


}
