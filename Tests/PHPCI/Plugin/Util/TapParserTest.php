<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Plugin\Util;

use PHPCI\Plugin\Util\TapParser;

class TapParserTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $content = <<<TAP
Leading garbage !
TAP version 13
ok 1 - SomeTest::testAnother
not ok
1..2
Trailing garbage !
TAP;
        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(array(
            array('pass' => true, 'severity' => 'success', 'message' => 'SomeTest::testAnother'),
            array('pass' => false, 'severity' => 'fail', 'message' => ''),
        ), $result);

        $this->assertEquals(1, $parser->getTotalFailures());
    }

    public function testSimple2()
    {
        $content = <<<TAP
Leading garbage !
TAP version 13

ok 1 - SomeTest::testAnother
not ok
1..2

TAP;
        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(array(
            array('pass' => true, 'severity' => 'success', 'message' => 'SomeTest::testAnother'),
            array('pass' => false, 'severity' => 'fail', 'message' => ''),
        ), $result);

        $this->assertEquals(1, $parser->getTotalFailures());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessageRegExp /No TAP/
     */
    public function testNoTapData()
    {
        $content = <<<TAP
Only garbage !
TAP;
        $parser = new TapParser($content);
        $parser->parse();
    }

    public function testTapCoverage()
    {
        $content = <<<TAP
TAP version 13

Generating code coverage report in HTML format ... done

TAP;
        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(array(), $result);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessageRegExp /Duplicated TAP/
     */
    public function testDuplicateOutput()
    {
        $content = <<<TAP
TAP version 13
TAP version 13
ok 1 - SomeTest::testAnother
ok 1 - SomeTest::testAnother
not ok - Failure: SomeTest::testAnother
not ok - Failure: SomeTest::testAnother
not ok 3 - Error: SomeTest::testAnother
not ok 3 - Error: SomeTest::testAnother
1..3
1..3
TAP;
        $parser = new TapParser($content);
        $parser->parse();
    }

    public function testSuiteAndTest()
    {
        $content = <<<TAP
TAP version 13
ok 1 - SomeTest::testAnother
not ok - Failure: SomeTest::testAnother
not ok 3 - Error: SomeTest::testAnother
1..3
Trailing garbage !
TAP;
        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(array(
            array('pass' => true, 'severity' => 'success', 'message' => 'SomeTest::testAnother',),
            array('pass' => false, 'severity' => 'fail', 'message' => 'Failure: SomeTest::testAnother'),
            array('pass' => false, 'severity' => 'error', 'message' => 'Error: SomeTest::testAnother'),
        ), $result);

        $this->assertEquals(2, $parser->getTotalFailures());
    }


    public function testSkipped()
    {
        $content = <<<TAP
TAP version 13
ok 1 - # SKIP
ok 2 - # SKIP foobar
ok 3 - foo # SKIP bar
1..3
TAP;
        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(array(
            array('pass' => true, 'severity' => 'skipped', 'message' => ''),
            array('pass' => true, 'severity' => 'skipped', 'message' => 'foobar'),
            array('pass' => true, 'severity' => 'skipped', 'message' => 'foo, skipped: bar'),
        ), $result);

        $this->assertEquals(0, $parser->getTotalFailures());
    }

    public function testTodo()
    {
        $content = <<<TAP
TAP version 13
ok 1 - SomeTest::testAnother # TODO really implement this test
ok 2 - # TODO really implement this test
ok 3 - this is a message # TODO really implement this test
ok 4 - # TODO
1..4
TAP;
        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(array(
            array('pass' => true, 'severity' => 'todo', 'message' => 'SomeTest::testAnother, todo: really implement this test'),
            array('pass' => true, 'severity' => 'todo', 'message' => 'really implement this test'),
            array('pass' => true, 'severity' => 'todo', 'message' => 'this is a message, todo: really implement this test'),
            array('pass' => true, 'severity' => 'todo', 'message' => ''),
        ), $result);

        $this->assertEquals(0, $parser->getTotalFailures());
    }

    public function testYamlDiagnostic()
    {
        // From https://phpunit.de/manual/current/en/logging.html#logging.tap
        $content = <<<TAP
    TAP version 13
not ok 1 - FOO
  ---
  message: BAR
  ...
1..1
TAP;
        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(array(
            array(
                'pass'     => false,
                'severity' => 'fail',
                'message'  => 'FOO' . PHP_EOL . 'BAR',
            ),
        ), $result);

        $this->assertEquals(1, $parser->getTotalFailures());
    }

    public function testFailureAndError()
    {
        // From https://phpunit.de/manual/current/en/logging.html#logging.tap
        $content = <<<TAP
    TAP version 13
not ok 1 - Failure: testFailure::FailureErrorTest
not ok 2 - Error: testError::FailureErrorTest
1..2
TAP;
        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(array(
            array(
                'pass'     => false,
                'severity' => 'fail',
                'message'  => 'Failure: testFailure::FailureErrorTest',
            ),
            array(
                'pass'     => false,
                'severity' => 'error',
                'message'  => 'Error: testError::FailureErrorTest',
            )
        ), $result);

        $this->assertEquals(2, $parser->getTotalFailures());
    }

    /**
     * @expectedException \Exception
     */
    public function testGarbage()
    {
        $content = "Garbage !";

        $parser = new TapParser($content);
        $parser->parse();
    }

    /**
     * @expectedException \Exception
     */
    public function testInvalidTestCount()
    {
        $content = <<<TAP
    TAP version 13
ok 1 - SomeTest::testAnother
not ok
1..5
TAP;

        $parser = new TapParser($content);
        $parser->parse();
    }

    /**
     * @expectedException \Exception
     */
    public function testEndlessYaml()
    {
        $content = <<<TAP
    TAP version 13
ok 1 - SomeTest::testAnother
   ---
1..1
TAP;

        $parser = new TapParser($content);
        $parser->parse();
    }

    public function testCodeception()
    {
        $content = <<< TAP
TAP version 13
ok 1 - try to access the dashboard as a guest (Auth/GuestAccessDashboardAndRedirectCept)
ok 2 - see the login page (Auth/SeeLoginCept)
ok 3 - click forgot password and see the email form (Auth/SeeLoginForgotPasswordCept)
ok 4 - see powered by runmybusiness branding (Auth/ShouldSeePoweredByBrandingCept)
ok 5 - submit invalid credentials (Auth/SubmitLoginAndFailCept)
ok 6 - submit valid credentials and see the dashboard (Auth/SubmitLoginAndSucceedCept)
1..6
TAP;

        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(
            array(
                array('pass' => true, 'severity' => 'success', 'message' => 'try to access the dashboard as a guest (Auth/GuestAccessDashboardAndRedirectCept)'),
                array('pass' => true, 'severity' => 'success', 'message' => 'see the login page (Auth/SeeLoginCept)'),
                array('pass' => true, 'severity' => 'success', 'message' => 'click forgot password and see the email form (Auth/SeeLoginForgotPasswordCept)'),
                array('pass' => true, 'severity' => 'success', 'message' => 'see powered by runmybusiness branding (Auth/ShouldSeePoweredByBrandingCept)'),
                array('pass' => true, 'severity' => 'success', 'message' => 'submit invalid credentials (Auth/SubmitLoginAndFailCept)'),
                array('pass' => true, 'severity' => 'success', 'message' => 'submit valid credentials and see the dashboard (Auth/SubmitLoginAndSucceedCept)'),
            ),
            $result
        );

        $this->assertEquals(0, $parser->getTotalFailures());

    }
}
