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
    const TEST_LINE_PATTERN = '/^(ok|not ok)(?:\s+\d+)?(?:\s+\-)?\s*(.*?)(?:\s*#\s*(skip|todo)\s*(.*))?\s*$/i';
    const TEST_YAML_START = '/^(\s*)---/';
    const TEST_DIAGNOSTIC = '/^#/';

    /**
     * @var string
     */
    protected $tapString;

    /**
     * @var int
     */
    protected $failures = 0;

    /**
     * @var array
     */
    protected $lines;

    /**
     * @var integer
     */
    protected $lineNumber;

    /**
     * @var integer
     */
    protected $testCount;

    /**
     * @var array
     */
    protected $results;

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
        $this->lines = array_map('rtrim', $lines);
        $this->lineNumber = 0;

        // Look for the beggning of the TAP output
        do {
            $versionLine = $this->nextLine();
        } while ($versionLine !== false && substr($versionLine, 0, 12) !== 'TAP version ');

        if ($versionLine === false) {
            throw new Exception('No TAP log found, please check the configuration.');
        } elseif ($versionLine !== 'TAP version 13') {
            throw new Exception(Lang::get('tap_version'));
        }

        $this->testCount = false;
        $this->results = array();

        while (($this->testCount === false || count($this->results) < $this->testCount)
            && false !== ($line = $this->nextLine())) {
            $this->parseLine($line);
        }

        if (count($this->results) !== $this->testCount) {
            throw new Exception(Lang::get('tap_error'));
        }

        return $this->results;
    }

    /** Fetch the next line.
     *
     * @return string|false The next line or false if the end has been reached.
     */
    protected function nextLine()
    {
        if ($this->lineNumber < count($this->lines)) {
            return $this->lines[$this->lineNumber++];
        }
        return false;
    }

    /** Parse a single line.
     *
     * @param string $line
     */
    protected function parseLine($line)
    {
        if (preg_match(self::TEST_COUNTS_PATTERN, $line, $matches)) {
            $this->testCount = intval($matches[1]);

        } elseif (preg_match(self::TEST_DIAGNOSTIC, $line)) {
            return;

        } elseif (preg_match(self::TEST_LINE_PATTERN, $line, $matches)) {
            $this->results[] = $this->processTestLine(
                $matches[1],
                isset($matches[2]) ? $matches[2] : '',
                isset($matches[3]) ? $matches[3] : null,
                isset($matches[4]) ? $matches[4] : null
            );

        } elseif (preg_match(self::TEST_YAML_START, $line, $matches)) {
            $data = $this->processYamlBlock($matches[1]);
            $lastTest = count($this->results)-1;
            $this->results[$lastTest] = array_merge($this->results[$lastTest], $data);

        } else {
            throw new Exception(sprintf('Incorrect TAP data, line %d: %s', $this->lineNumber, $line));
        }
    }

    /**
     * Process an individual test line.
     *
     * @param string $result
     * @param string $message
     * @param string $directive
     * @param string $reason
     *
     * @return array
     */
    protected function processTestLine($result, $message, $directive, $reason)
    {
        $test = array(
            'pass'     => true,
            'message'  => $message,
            'severity' => 'success',
        );

        if ($result !== 'ok') {
            $test['pass'] = false;
            $test['severity'] = 'fail';
            $this->failures++;
            if (preg_match('/^(Error|Failure):/', $message, $matches)) {
                $test['severity'] = $matches[1] === 'Error' ? 'error' : 'fail';
            }
        }

        if (preg_match('/(\\\\?\w+(?:\\\\\w+)*)::(\w+)/', $message, $matches)) {
            $test['suite'] = $matches[1];
            $test['test'] = $matches[2];
        }

        if ($directive) {
            $test = $this->processDirective($test, $directive, $reason);
        }

        return $test;
    }

    /** Process an indented Yaml block.
     *
     * @param string $indent The block indentation to ignore.
     *
     * @return array The processed Yaml content.
     */
    protected function processYamlBlock($indent)
    {
        $startLine = $this->lineNumber+1;
        $endLine = $indent.'...';
        $yamlLines = array();
        do {
            $line = $this->nextLine();
            if ($line === false) {
                throw new Exception(Lang::get('tap_error_endless_yaml', $startLine));
            } elseif ($line === $endLine) {
                break;
            }
            $yamlLines[] = substr($line, strlen($indent));

        } while (true);

        return Yaml::parse(join("\n", $yamlLines));
    }

    /** Process a TAP directive
     *
     * @param array $test
     * @param string $directive
     * @param string $reason
     * @return array
     */
    protected function processDirective($test, $directive, $reason)
    {
        $test['severity'] = strtolower($directive) === 'skip' ? 'skipped' : 'todo';

        if (!empty($reason)) {
            if (!empty($test['message'])) {
                $test['message'] .= ', '.$test['severity'].': ';
            }
            $test['message'] .= $reason;
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
