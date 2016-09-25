<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Plugin\Util;

use PHPCI\Plugin\Util\PhpUnitResult;

/**
 * Class PhpUnitResultTest parses the results for the PhpUnitV2 plugin
 * @author       Pablo Tejada <pablo@ptejada.com>
 * @package      PHPCI
 * @subpackage   Plugin
 */
class PhpUnitResultTest extends \PHPUnit_Framework_TestCase
{

    public function testInitParse()
    {
        $buildPath = '/path/to/build';
        $parser = new PhpUnitResult(PHPCI_DIR . 'Tests/PHPCI/Plugin/SampleFiles/phpunit_money.txt', $buildPath);
        $output = $parser->parse()->getResults();
        $errors = $parser->getErrors();

        $this->assertEquals(8, $parser->getFailures());
        $this->assertInternalType('array', $output);
        $this->assertInternalType('array', $errors);
        $this->assertNotEmpty($output);
        $this->assertNotEmpty($errors);

        // The trace elements should not include the build path
        $this->assertStringStartsNotWith($buildPath, $output[3]['trace'][0]);
        $this->assertStringStartsNotWith($buildPath, $output[3]['trace'][1]);

        $this->assertEquals(PhpUnitResult::SEVERITY_SKIPPED, $output[5]['severity']);
        $this->assertContains('Incomplete Test:', $output[5]['message']);

        $this->assertEquals(PhpUnitResult::SEVERITY_SKIPPED, $output[11]['severity']);
        $this->assertContains('Skipped Test:', $output[11]['message']);
    }

    public function testParseFailure()
    {
        $this->setExpectedException('\Exception', 'Failed to parse the JSON output');

        $buildPath = '/path/to/build';
        $parser = new PhpUnitResult(PHPCI_DIR . 'Tests/PHPCI/Plugin/SampleFiles/invalid_format.txt', $buildPath);
        $parser->parse();
    }
}
