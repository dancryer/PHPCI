<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2013, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Plugin;

use PHPCI\Plugin\Option\PhpUnitOptions;

/**
 * Unit test for the PHPUnitOptions parser
 *
 * @author Pablo Tejada <pablo@ptejada.com>
 */
class PhpUnitOptionsTest extends \PHPUnit_Framework_TestCase
{
    public function validOptionsProvider()
    {
        return array(
            array(
                array(
                    'config' => 'tests/phpunit.xml',
                    'args'   => '--stop-on-error --log-junit /path/to/log/',
                ),
                array(
                    'stop-on-error' => '',
                    'log-junit'     => '/path/to/log/',
                    'configuration' => 'tests/phpunit.xml',
                ),
            ),
            array(
                array(
                    'coverage' => '/path/to/coverage2/',
                    'args'     => array(
                        'coverage-html' => '/path/to/coverage1/',
                    ),
                ),
                array(
                    'coverage-html' => array(
                        '/path/to/coverage1/',
                        '/path/to/coverage2/',
                    ),
                ),
            ),
            array(
                array(
                    'directory' => array(
                        '/path/to/test1/',
                        '/path/to/test2/',
                    ),
                    'args'      => array(
                        'coverage-html' => '/path/to/coverage1/',
                    ),
                ),
                array(
                    'coverage-html' => '/path/to/coverage1/',
                ),
            ),
            array(
                array(
                    'config' => array('tests/phpunit.xml'),
                    'args'   => "--testsuite=unit --bootstrap=vendor/autoload.php",
                ),
                array(
                    'testsuite'     => 'unit',
                    'bootstrap'     => 'vendor/autoload.php',
                    'configuration' => array('tests/phpunit.xml'),
                ),
            ),
            array(
                array(
                    'config' => array('tests/phpunit.xml'),
                    'args'   => "--testsuite='unit' --bootstrap 'vendor/autoload.php'",
                ),
                array(
                    'testsuite'     => 'unit',
                    'bootstrap'     => 'vendor/autoload.php',
                    'configuration' => array('tests/phpunit.xml'),
                ),
            ),
            array(
                array(
                    'config' => array('tests/phpunit.xml'),
                    'args'   => '--testsuite="unit" --bootstrap "vendor/autoload.php"',
                ),
                array(
                    'testsuite'     => 'unit',
                    'bootstrap'     => 'vendor/autoload.php',
                    'configuration' => array('tests/phpunit.xml'),
                ),
            ),
        );
    }

    /**
     * @param $rawOptions
     * @param $parsedArguments
     *
     * @dataProvider validOptionsProvider
     */
    public function testCommandArguments($rawOptions, $parsedArguments)
    {
        $options = new PhpUnitOptions($rawOptions);
        $this->assertSame($parsedArguments, $options->getCommandArguments());
    }

    public function testGetters()
    {
        $options = new PhpUnitOptions(
            array(
                'run_from' => '/path/to/run/from',
                'path'     => 'subTest',
            )
        );

        $this->assertEquals('/path/to/run/from', $options->getRunFrom());
        $this->assertEquals('subTest', $options->getTestsPath());
        $this->assertNull($options->getOption('random'));
        $this->assertEmpty($options->getDirectories());
        $this->assertEmpty($options->getConfigFiles());

        $files = $options->getConfigFiles(PHPCI_DIR);

        $this->assertFileExists(PHPCI_DIR . $files[0]);
    }
}
