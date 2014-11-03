<?php

namespace PHPCI\Plugin\Util\TestResultParsers;

interface ParserInterface
{

    /**
     * @return array An array of key/value pairs for storage in the plugins result metadata
     */
    public function parse();

    public function getTotalTests();
    public function getTotalTimeTaken();
    public function getTotalFailures();
}
