<?php

namespace PHPCI\Plugin\Util;

use PHPCI\Plugin\Util\TestResultParsers\ParserInterface;
use SimpleXMLElement;

/**
 * Processes JUnit XML log file into usable test result data.
 * @package PHPCI\Plugin\Util
 */
class JUnitParser implements ParserInterface
{
    protected $xml = null;
    protected $failures = 0;
    protected $number_tests = 0;
    protected $duration_time = 0;

    public function __construct($xml_string)
    {
        $this->xml = new SimpleXMLElement($xml_string);
    }

    /**
     * @return array An array of key/value pairs for storage in the plugins result metadata
     */
    public function parse()
    {
        $attr = $this->xml->testsuite->attributes();
        $this->duration = floatval($attr['time']);
        $this->number_tests = intval($attr['tests']);
        $this->failures = intval($attr['failures']);

        $raw_data = $this->parseTestSuites($this->xml);

        //we want to log individual test cases in JSON format for meta data and flatten results
        $data = [];
        foreach ($raw_data as $suites) {
            foreach ($suites as $suite) {
                $suite_name = $suite['name'];
                foreach ($suite['cases'] as $test_case) {
                    $result = [
                        'pass' => true,
                        'message' => $suite_name . '::' . $test_case['name'],
                        'severity' => 'success',
                    ];

                    if (isset($test_case['error'])) {
                        $result = [
                            'pass' => false,
                            'message' => $test_case['error']['message'],
                            'severity' => strpos($test_case['error']['type'], 'Error') !== false ? 'error' : 'fail'
                        ];
                    }

                    $data[] = $result;
                }
            }
        }

        return $data;
    }

    public function getTotalTests()
    {
        return $this->number_tests;
    }

    public function getTotalTimeTaken()
    {
        return $this->duration;
    }

    public function getTotalFailures()
    {
        return $this->failures;
    }

    //NOTE: this is modified from https://github.com/Block8/PHPCI/blob/2ddda7711e1272cf6591f274e09d45b9865f4a60/PHPCI/Plugin/PhpSpec.php
    protected function parseTestSuites(SimpleXMLElement $xml)
    {
        $data = [];

        /**
         * @var \SimpleXMLElement $group
         */
        foreach ($xml->testsuite->testsuite as $group) {
            $attr = $group->attributes();

            $suite = array(
                'name' => (String)$attr['name'],
                'time' => (float)$attr['time'],
                'tests' => (int)$attr['tests'],
                'failures' => (int)$attr['failures'],
                'errors' => (int)$attr['errors'],
                // now the cases
                'cases' => array()
            );

            /**
             * @var \SimpleXMLElement $child
             */
            foreach ($group->testcase as $child) {
                $attr = $child->attributes();

                $case = array(
                    'name' => (String)$attr['name'],
                    'classname' => (String)$attr['class'],
                    'filename' => (String)$attr['file'],
                    'line' => (String)$attr['line'],
                    'time' => (float)$attr['time'],
                );

                $error = [];
                foreach ($child->failure as $f) {
                    $error['type'] = $f->attributes()['type'];
                    $error['message'] = (String)$f;
                }
                foreach ($child->{'system-err'} as $system_err) {
                    $error['raw'] = (String)$system_err;
                }

                if (!empty($error)) {
                    $case['error'] = $error;
                }

                $suite['cases'][] = $case;
            }
            $data['suites'][] = $suite;
        }

        return $data;
    }
}
