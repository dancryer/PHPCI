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
    const TEST_LINE_PATTERN = '/(ok|not ok)\s+[0-9]+\s+\-\s+([^\n]+)::([^\n]+)/';
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
     * Parse a given TAP format string and return an array of tests and their status.
     */
    public function parse()
    {
        // Split up the TAP string into an array of lines, then
        // trim all of the lines so there's no leading or trailing whitespace.
        $lines = explode("\n", $this->tapString);
        $lines = array_map(function ($line) {
            return trim($line);
        }, $lines);

        // Check TAP version:
        $versionLine = array_shift($lines);

        if ($versionLine != 'TAP version 13') {
            throw new \Exception(Lang::get('tap_version'));
        }

        if (isset($lines[count($lines) - 1]) && preg_match(self::TEST_COVERAGE_PATTERN, $lines[count($lines) - 1])) {
            array_pop($lines);
            if ($lines[count($lines) - 1] == "") {
                array_pop($lines);
            }
        }

        $matches = array();
        $totalTests = 0;
        if (preg_match(self::TEST_COUNTS_PATTERN, $lines[0], $matches)) {
            array_shift($lines);
            $totalTests = (int) $matches[2];
        }

        if (isset($lines[count($lines) - 1]) &&
            preg_match(self::TEST_COUNTS_PATTERN, $lines[count($lines) - 1], $matches)) {
            array_pop($lines);
            $totalTests = (int) $matches[2];
        }

        $rtn = $this->processTestLines($lines);

        if ($totalTests != count($rtn)) {
            throw new \Exception(Lang::get('tap_error'));
        }

        return $rtn;
    }

    /**
     * Process the individual test lines within a TAP string.
     * @param $lines
     * @return array
     */
    protected function processTestLines($lines)
    {
        $rtn = array();

        foreach ($lines as $line) {
            $matches = array();

            if (preg_match(self::TEST_LINE_PATTERN, $line, $matches)) {
                $ok = ($matches[1] == 'ok' ? true : false);

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
