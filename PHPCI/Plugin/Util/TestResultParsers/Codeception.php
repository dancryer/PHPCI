<?php

namespace PHPCI\Plugin\Util\TestResultParsers;

class Codeception implements ParserInterface
{
    public $resultsXml;

    protected $results;

    protected $totalTests;
    protected $totalTimeTaken;
    protected $totalFailures;

    public function __construct($resultsXml)
    {
        $this->resultsXml = $resultsXml;

        $this->totalTests = 0;
    }

    /**
     * @return array An array of key/value pairs for storage in the plugins result metadata
     */
    public function parse()
    {
        $rtn = array();

        $this->results = new \SimpleXMLElement($this->resultsXml);

        // calculate total results
        foreach($this->results->testsuite as $testsuite) {
            $this->totalTests += (int) $testsuite['tests'];
            $this->totalTimeTaken += (float) $testsuite['time'];
            $this->totalFailures += (int) $testsuite['failures'];

            foreach($testsuite->testcase as $testcase) {
                $testresult = array(
                    'suite' => (string) $testsuite['name'],
                    'name' => (string) $testcase['name'],
                    'class' => (string) $testcase['class'],
                    'feature' => (string) $testcase['feature'],
                    'assertions' => (int) $testcase['assertions'],
                    'time' => (float) $testcase['time']
                );

                if (isset($testcase->failure)) {
                    $testresult['pass'] = false;
                    $testresult['message'] = (string) $testcase->failure;
                } else {
                    $testresult['pass'] = true;
                }

                $rtn[] = $testresult;
            }
        }

        return $rtn;
    }

    public function getTotalTests()
    {
        return $this->totalTests;
    }

    public function getTotalTimeTaken()
    {
        return $this->totalTimeTaken;
    }

    public function getTotalFailures()
    {
        return $this->totalFailures;
    }
}
