<?php

namespace PHPCI\Plugin\Util;

use Exception;
use PHPCI\Helper\Lang;
use Symfony\Component\Yaml\Yaml;

/**
 * Processes TAP format strings into usable test result data.
 * @package PHPCI\Plugin\Util
 */
class TapParser
{
    const TEST_COUNTS_PATTERN = '/^\d+\.\.(\d+)/';
    const TEST_LINE_PATTERN = '/^(ok|not ok)(?:\s+(\d+))?(?:\s+\-)?\s*(.*?)(?:\s*#\s*(skip|todo)\s*(.*))?\s*$/i';
    const TEST_YAML_START = '/^(\s*)---/';
    const TEST_DIAGNOSTIC = '/^#/';

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
        $lines = array_map('rtrim', $lines);

        // Check TAP version:
        $versionLine = array_shift($lines);

        if ($versionLine != 'TAP version 13') {
            throw new Exception(Lang::get('tap_version'));
        }

        $matches;
        $results = array();
        $totalTests = false;
        $totalLines = count($lines);
        $numTests = 0;

        for ($lineNumber = 0;
            $lineNumber < $totalLines && ($totalTests === false || $numTests < $totalTests);
            $lineNumber++) {
            $line = $lines[$lineNumber];

            if (preg_match(self::TEST_COUNTS_PATTERN, $line, $matches)) {
                $totalTests = intval($matches[1]);
            } elseif (preg_match(self::TEST_DIAGNOSTIC, $line)) {
                continue;
            } elseif (preg_match(self::TEST_LINE_PATTERN, $line, $matches)) {
                $results[] = $this->processTestLine($matches);
                $numTests++;
            } elseif (preg_match(self::TEST_YAML_START, $line, $matches)) {
                $indent = $matches[1];
                $endLine = $indent.'...';
                $yamlLines = array();
                for ($yamlEnd = $lineNumber + 1; $yamlEnd < $totalLines && $endLine !== $lines[$yamlEnd]; $yamlEnd++) {
                    $yamlLines[] = substr($lines[$yamlEnd], strlen($indent));
                }
                if ($yamlEnd >= $totalLines) {
                    throw new Exception(Lang::get('tap_error_endless_yaml', $lineNumber));
                }
                $data = Yaml::parse(join("\n", $yamlLines));
                $results[$numTests-1] = array_merge($results[$numTests-1], $data);
                $lineNumber = $yamlEnd;
            } else {
                throw new Exception(sprintf('Incorrect TAP data, line %d: %s', $lineNumber, $line));
            }
        }

        if ($numTests != $totalTests) {
            throw new Exception(Lang::get('tap_error'));
        }

        return $results;
    }

    /**
     * Process an individual test line.
     *
     * @param array $matches The regex matches.
     *
     * @return array
     */
    protected function processTestLine($matches)
    {
        $test = array();
        $test['pass'] = ($matches[1] === 'ok');
        if (!$test['pass']) {
            $this->failures++;
        }

        if (preg_match('/(?:(Error|Failure):\s*)?(\S+)::(\S+)/', $matches[3], $moreMatches)) {
            if ($moreMatches[1] === 'Error') {
                $test['severity'] = 'error';
            } elseif ($moreMatches[1] === 'Failure') {
                $test['severity'] = 'fail';
            }
            $test['suite'] = $moreMatches[2];
            $test['test'] = $moreMatches[3];
        }

        if (isset($matches[4])) {
            switch (strtolower($matches[4])) {
                case 'skip':
                    $test['skipped'] = true;
                    $test['message'] = $matches[5] ?: "skipped";
                    break;
                case 'todo':
                    $test['todo'] = $matches[5] ?: "todo";
                    break;
            }
        }

        return $test;
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
