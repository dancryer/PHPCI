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
    const TEST_LINE_PATTERN   = '/^(ok|not ok)(?:\s+\d+)?(?:\s+\-)?\s*(.*?)(?:\s*#\s*(skip|todo)\s*(.*))?\s*$/i';
    const TEST_YAML_START     = '/^(\s*)---/';
    const TEST_DIAGNOSTIC     = '/^#/';
    const TEST_COVERAGE       = '/^Generating/';

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
        $this->lines = array_map('rtrim', explode("\n", $this->tapString));
        $this->lineNumber = 0;

        $this->testCount = false;
        $this->results = array();

        $header = $this->findTapLog();

        $line = $this->nextLine();
        if ($line === $header) {
            throw new Exception("Duplicated TAP log, please check the configuration.");
        }

        while ($line !== false && ($this->testCount === false || count($this->results) < $this->testCount)) {
            $this->parseLine($line);
            $line = $this->nextLine();
        }

        if (false !== $this->testCount && count($this->results) !== $this->testCount) {
            throw new Exception(Lang::get('tap_error'));
        }

        return $this->results;
    }

    /** Looks for the start of the TAP log in the string.
     *
     * @return string The TAP header line.
     *
     * @throws Exception if no TAP log is found or versions mismatch.
     */
    protected function findTapLog()
    {
        // Look for the beginning of the TAP output
        do {
            $header = $this->nextLine();
        } while ($header !== false && substr($header, 0, 12) !== 'TAP version ');

        //
        if ($header === false) {
            throw new Exception('No TAP log found, please check the configuration.');
        } elseif ($header !== 'TAP version 13') {
            throw new Exception(Lang::get('tap_version'));
        }

        return $header;
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

    /**
     * @param string $line
     *
     * @return boolean
     */
    protected function testLine($line)
    {
        if (preg_match(self::TEST_LINE_PATTERN, $line, $matches)) {
            $this->results[] = $this->processTestLine(
                $matches[1],
                isset($matches[2]) ? $matches[2] : '',
                isset($matches[3]) ? $matches[3] : null,
                isset($matches[4]) ? $matches[4] : null
            );

            return true;
        }

        return false;
    }

    /**
     * @param string $line
     *
     * @return boolean
     */
    protected function yamlLine($line)
    {
        if (preg_match(self::TEST_YAML_START, $line, $matches)) {
            $diagnostic = $this->processYamlBlock($matches[1]);
            $test       = array_pop($this->results);
            if (isset($test['message'], $diagnostic['message'])) {
                $test['message'] .= PHP_EOL . $diagnostic['message'];
                unset($diagnostic['message']);
            }
            $this->results[] = array_replace($test, $diagnostic);

            return true;
        }

        return false;
    }

    /** Parse a single line.
     *
     * @param string $line
     *
     * @throws Exception
     */
    protected function parseLine($line)
    {
        if (preg_match(self::TEST_DIAGNOSTIC, $line) || preg_match(self::TEST_COVERAGE, $line) || !$line) {
            return;
        }

        if (preg_match(self::TEST_COUNTS_PATTERN, $line, $matches)) {
            $this->testCount = intval($matches[1]);

            return;
        }

        if ($this->testLine($line)) {
            return;
        }

        if ($this->yamlLine($line)) {
            return;
        }

        throw new Exception(sprintf('Incorrect TAP data, line %d: %s', $this->lineNumber, $line));
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
            $test['severity'] = substr($message, 0, 6) === 'Error:' ? 'error' : 'fail';
            $this->failures++;
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
        $startLine = $this->lineNumber + 1;
        $endLine   = $indent . '...';
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
