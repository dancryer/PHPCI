<?php
namespace PHPCI\Plugin\Tests\Util;

use PHPCI\Plugin\Util\TapParser;

class TapParserTest extends \PHPUnit_Framework_TestCase
{
    public function testSkipped()
    {
        $content = <<<TAP
TAP version 13
ok 1 - SomeTest::testAnother
ok 2 - # SKIP
1..2
TAP;
        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(array(
            array('pass' => true, 'suite' => 'SomeTest', 'test' => 'testAnother'),
            array('message' => 'SKIP'),
        ), $result);

        $this->assertEquals(0, $parser->getTotalFailures());
    }
}
