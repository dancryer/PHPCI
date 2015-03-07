<?php
namespace PHPCI\Plugin\Tests\Util;

use PHPCI\Helper\Lang;
use PHPCI\Plugin\Util\TapParser;

class TapParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test processing of coverage info
     */
    public function testOnlyCounts()
    {
        $parser = new TapParser(file_get_contents(__DIR__ . '/tap_samples/only_counts.txt'));

        // test prepareLines()
        $preparedLines = $parser->prepareLines();
        $this->assertEquals(array(
            'TAP version 13',
            '0..0'
        ), $preparedLines);

        // test getTotalFailures()
        $result = $parser->parse();
        $this->assertEmpty($result);
        $this->assertEquals(0, $parser->getTotalFailures());

        // test parseTotalTests()
        $this->assertSame(0, $parser->parseTotalTests($preparedLines));
    }

    /**
     * Test processing of coverage info
     */
    public function testCoverage()
    {
        $parser = new TapParser(file_get_contents(__DIR__ . '/tap_samples/coverage.txt'));

        // test prepareLines()
        $preparedLines = $parser->prepareLines();
        $this->assertEquals(array(
            'TAP version 13',
            'ok 1 - testNewArrayIsEmpty(ArrayTest)',
            'ok 2 - testArrayContainsAnElement(ArrayTest)',
            '1..2'
        ), $preparedLines);

        // test getTotalFailures()
        $result = $parser->parse();
        $this->assertNotEmpty($result);

        $this->assertEquals(array(
            array('pass' => true, 'test' => 'testNewArrayIsEmpty', 'message' => 'ok'),
            array('pass' => true, 'test' => 'testArrayContainsAnElement', 'message' => 'ok')
        ), $result);
        $this->assertEquals(0, $parser->getTotalFailures());

        // test parseTotalTests()
        $this->assertSame(2, $parser->parseTotalTests($preparedLines));
    }

    /**
     * Test processing of mixed results
     */
    public function testMixedWithComments()
    {
        $parser = new TapParser(file_get_contents(__DIR__ . '/tap_samples/mixed_success_with_comment.txt'));

        // test prepareLines()
        $preparedLines = $parser->prepareLines();
        $this->assertEquals(array(
            'TAP version 13',
            'ok 1 - retrieving servers from the database',
            '# need to ping 6 servers',
            'ok 2 - pinged diamond',
            'ok 3 - pinged ruby',
            'not ok 4 - pinged saphire',
            'ok 5 - pinged onyx',
            'not ok 6 - pinged quartz',
            'ok 7 - pinged gold',
            '1..7'
        ), $preparedLines);

        // test getTotalFailures()
        $result = $parser->parse();
        $this->assertNotEmpty($result);
        $this->assertEquals(array(
            array(
                'pass' => true,
                'message' => "ok",
                'test' => 'retrieving servers from the database'
            ),
            array(
                'pass' => true,
                'message' => "ok",
                'test' => 'pinged diamond'
            ),
            array(
                'pass' => true,
                'message' => "ok",
                'test' => 'pinged ruby'
            ),
            array(
                'pass' => false,
                'message' => "not ok",
                'test' => 'pinged saphire'
            ),
            array(
                'pass' => true,
                'message' => "ok",
                'test' => 'pinged onyx'
            ),
            array(
                'pass' => false,
                'message' => "not ok",
                'test' => 'pinged quartz'
            ),
            array(
                'pass' => true,
                'message' => "ok",
                'test' => 'pinged gold'
            )
        ), $result);
        $this->assertEquals(2, $parser->getTotalFailures());

        // test parseTotalTests()
        $this->assertSame(7, $parser->parseTotalTests($preparedLines));
    }

    /**
     * Test TAP lines without TAP version info
     */
    public function testNoVersion()
    {
        $parser = new TapParser(file_get_contents(__DIR__ . '/tap_samples/no_version.txt'));

        try {
            $parser->parse();
            $this->fail("Exception expected when TAP version info is missing");
        } catch (\Exception $e) {
            $this->assertSame(Lang::get('tap_version'), $e->getMessage());
        }
    }

    /**
     * Test TAP lines with only failed tests.
     */
    public function testFail()
    {
        $parser = new TapParser(file_get_contents(__DIR__ . '/tap_samples/only_failures.txt'));

        // test prepareLines()
        $preparedLines = $parser->prepareLines();
        $this->assertEquals(array(
            'TAP version 13',
            'not ok 1 - Failure: testFailure(FailureErrorTest)',
            '---',
            "message: 'Failed asserting that <integer:2> matches expected value <integer:1>.'",
            'severity: fail',
            'data:',
            'got: 2',
            'expected: 1',
            '...',
            'not ok 2 - Error: testError(FailureErrorTest)',
            '1..2'

        ), $preparedLines);

        // test getTotalFailures()
        $result = $parser->parse();
        $this->assertEquals(array(
            array(
                'pass' => false,
                'suite' => 'Failure',
                'test' => ' testFailure(FailureErrorTest)',
                'message' => "Failed asserting that <integer:2> matches expected value <integer:1>."
            ),
            array(
                'pass' => false,
                'suite' => 'Error',
                'test' => ' testError(FailureErrorTest)'
            )
        ), $result);
        $this->assertEquals(2, $parser->getTotalFailures());

        // test parseTotalTests()
        $this->assertSame(2, $parser->parseTotalTests($preparedLines));
    }

    /**
     * Test processing of successes
     */
    public function testOnlySuccesses()
    {
        $parser = new TapParser(file_get_contents(__DIR__ . '/tap_samples/only_successes.txt'));

        // test prepareLines()
        $preparedLines = $parser->prepareLines();
        $this->assertEquals(array(
            'TAP version 13',
            'ok 1 - testNewArrayIsEmpty(ArrayTest)',
            'ok 2 - testArrayContainsAnElement(ArrayTest)',
            '1..2'
        ), $preparedLines);

        // test getTotalFailures()
        $result = $parser->parse();
        $this->assertNotEmpty($result);

        $this->assertEquals(array(
            array('pass' => true, 'test' => 'testNewArrayIsEmpty', 'message' => 'ok'),
            array('pass' => true, 'test' => 'testArrayContainsAnElement', 'message' => 'ok')
        ), $result);
        $this->assertEquals(0, $parser->getTotalFailures());

        // test parseTotalTests()
        $this->assertSame(2, $parser->parseTotalTests($preparedLines));
    }

    /**
     * Test output with skipped tests
     */
    public function testSkippedTests()
    {
        $parser = new TapParser(file_get_contents(__DIR__ . '/tap_samples/skipped.txt'));

        // test prepareLines()
        $preparedLines = $parser->prepareLines();
        $this->assertEquals(array(
            "TAP version 13",
            "ok 1 - SomeTest::testAnother",
            "ok 2 - # SKIP",
            "1..2"
        ), $preparedLines);

        // test getTotalFailures()
        $result = $parser->parse();
        $this->assertEquals(array(
            array('pass' => true, 'suite' => 'SomeTest', 'test' => 'testAnother'),
            array('message' => 'SKIP'),
        ), $result);
        $this->assertEquals(0, $parser->getTotalFailures());

        // test parseTotalTests()
        $this->assertSame(2, $parser->parseTotalTests($preparedLines));
    }

    /**
     * Induce parse() mismatch exception
     */
    public function testParseMismatch()
    {
        $parser = new TapParser(file_get_contents(__DIR__ . '/tap_samples/parse_mismatch.txt'));

        try {
            $parser->parse();
            $this->fail("Exception expected on parseTotalTests() != rtn mismatch");
        } catch (\Exception $e) {
            $this->assertSame(Lang::get('tap_error'), $e->getMessage());
        }
    }

    /**
     * Test TAP lines with skipped tests and white space
     */
    public function testSkippedWhiteSpace()
    {
        $parser = new TapParser(file_get_contents(__DIR__ . '/tap_samples/whitespace.txt'));
        $result = $parser->parse();

        $this->assertEquals(array(
            array('pass' => true, 'suite' => 'SomeTest', 'test' => 'testAnother'),
            array('message' => 'SKIP'),
        ), $result);

        $this->assertEquals(0, $parser->getTotalFailures());
    }
}
