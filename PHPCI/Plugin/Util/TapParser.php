<?php

namespace PHPCI\Plugin\Util;

use PHPCI\Helper\Lang;

/**
 * Processes TAP format strings into usable test result data.
 * @package PHPCI\Plugin\Util
 */
class TapParser
{
    const TEST_COUNTS_PATTERN = '/([0-9]+)\.\.([0-9]+)/';
    const TEST_LINE_PATTERN = '/(ok|not ok)\s+[0-9]+\s+\-\s+([^\n:]+):{1,2}([^\n]+)/';
    const TEST_GENERIC_LINE_PATTERN = '/(ok|not ok)\s+[0-9]+\s+\-\s+([^\n:\(]+)/';
    const TEST_MESSAGE_PATTERN = '/message\:\s+\'([^\']+)\'/';
    const TEST_COVERAGE_PATTERN = '/Generating code coverage report/';
    const TEST_SKIP_PATTERN = '/ok\s+[0-9]+\s+\-\s+#\s+SKIP/';

    /**
     * @var string
     */
    protected $tapString;
    protected $failures = 0;

    /**
     * Create a new TAP parser for a given string.
     * @param string $tapString The TAP format string to be parsed.
     */
    public function __construct($tapString)
    {
        $this->tapString = trim($tapString);
    }

    /**
     * Split up the TAP string into an array of lines, then
     * trim all of the lines so there's no leading or trailing whitespace. Remove NULL items.
     * @return array
     */
    public function prepareLines()
    {
        return array_filter(array_map(function ($line) {
            if (empty($line) || preg_match(self::TEST_COVERAGE_PATTERN, $line)) {
                return null;
            }
            return trim($line);
        }, explode("\n", $this->tapString)));
    }

    /**
     * Parse a given TAP format string and return an array of tests and their status.
     */
    public function parse()
    {
        $lines = $this->prepareLines();

        // Check TAP version:
        $versionLine = array_shift($lines);

        if ($versionLine != 'TAP version 13') {
            throw new \Exception(Lang::get('tap_version'));
        }

        $rtn = $this->processTestLines($lines);

        if ($this->parseTotalTests($lines) != count($rtn)) {
            throw new \Exception(Lang::get('tap_error'));
        }

        return $rtn;
    }

    /**
     * Get total tests
     * @param array $lines Lines
     * @return int
     */
    public function parseTotalTests(array $lines = array())
    {
        if (preg_match(self::TEST_COUNTS_PATTERN, $lines[0], $matches)) {
            array_shift($lines);
        }

        if (isset($lines[count($lines) - 1]) &&
            preg_match(self::TEST_COUNTS_PATTERN, $lines[count($lines) - 1], $matches)
        ) {
            array_pop($lines);
        }

        return isset($matches[2]) ? (int)$matches[2] : 0;
    }

    /**
     * Process the individual test lines within a TAP string.
     * @param Array $lines Lines
     * @param Array $rtn Optional predefined result
     * @return array
     */
    protected function processTestLines($lines, array $rtn = array())
    {
        foreach ($lines as $line) {
            if (preg_match(self::TEST_LINE_PATTERN, $line, $matches)) {
                $ok = ($matches[1] == 'ok');

                if (!$ok) {
                    $this->failures++;
                }

                $item = array(
                    'pass' => $ok,
                    'suite' => $matches[2],
                    'test' => $matches[3],
                );

                $rtn[] = $item;
            } elseif (preg_match(self::TEST_SKIP_PATTERN, $line, $matches)) {
                $rtn[] = array('message' => 'SKIP');
            } elseif (preg_match(self::TEST_MESSAGE_PATTERN, $line, $matches)) {
                $rtn[count($rtn) - 1]['message'] = $matches[1];
            } elseif (preg_match(self::TEST_GENERIC_LINE_PATTERN, $line, $matches)) {
                $ok = ($matches[1] == 'ok');

                if (!$ok) {
                    $this->failures++;
                }

                $item = array(
                    'pass' => $ok,
                    'test' => $matches[2],
                    'message' => $matches[1],
                );

                $rtn[] = $item;
            }
        }

        return $rtn;
    }

    /**
     * Get the total number of failures from the current TAP file.
     * @return int
     */
    public function getTotalFailures()
    {
        return $this->failures;
    }
}
