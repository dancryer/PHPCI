<?php
namespace PHPCI\Plugin\Tests\Util;

use PHPCI\Plugin\Util\TapParser;

class TapParserTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $content = <<<TAP
TAP version 13
ok 1 - SomeTest::testAnother
not ok
1..2
Trailing garbage !
TAP;
        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(array(
            array('pass' => true, 'suite' => 'SomeTest', 'test' => 'testAnother'),
            array('pass' => false),
        ), $result);

        $this->assertEquals(1, $parser->getTotalFailures());
    }

    public function testSkipped()
    {
        $content = <<<TAP
TAP version 13
ok 1 - SomeTest::testAnother
ok 2 - # SKIP
ok 3 - # SKIP foobar
1..3
TAP;
        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(array(
            array('pass' => true, 'suite' => 'SomeTest', 'test' => 'testAnother'),
            array('pass' => true, 'skipped' => true, 'message' => 'skipped'),
            array('pass' => true, 'skipped' => true, 'message' => 'foobar'),
        ), $result);

        $this->assertEquals(0, $parser->getTotalFailures());
    }

    public function testTodo()
    {
        $content = <<<TAP
TAP version 13
ok 1 - SomeTest::testAnother # TODO really implement this test
1..1
TAP;
        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(array(
            array('pass' => true, 'suite' => 'SomeTest', 'test' => 'testAnother', 'todo' => 'really implement this test'),
        ), $result);

        $this->assertEquals(0, $parser->getTotalFailures());
    }

    public function testFailureAnderror()
    {
        // From https://phpunit.de/manual/current/en/logging.html#logging.tap
        $content = <<<TAP
    TAP version 13
not ok 1 - Failure: testFailure::FailureErrorTest
  ---
  message: 'Failed asserting that <integer:2> matches expected value <integer:1>.'
  severity: fail
  data:
    got: 2
    expected: 1
  ...
not ok 2 - Error: testError::FailureErrorTest
1..2
TAP;
        $parser = new TapParser($content);
        $result = $parser->parse();

        $this->assertEquals(array(
            array(
                'pass'     => false,
                'suite'    => 'testFailure',
                'test'     => 'FailureErrorTest',
                'severity' => 'fail',
                'message'  => 'Failed asserting that <integer:2> matches expected value <integer:1>.',
                'data'     => array(
                    'got' => 2,
                    'expected' => 1
                )
            ),
            array(
                'pass'     => false,
                'severity' => 'error',
                'suite'    => 'testError',
                'test'     => 'FailureErrorTest'
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
                array('pass' => true, 'message' => 'try to access the dashboard as a guest (Auth/GuestAccessDashboardAndRedirectCept)'),
                array('pass' => true, 'message' => 'see the login page (Auth/SeeLoginCept)'),
                array('pass' => true, 'message' => 'click forgot password and see the email form (Auth/SeeLoginForgotPasswordCept)'),
                array('pass' => true, 'message' => 'see powered by runmybusiness branding (Auth/ShouldSeePoweredByBrandingCept)'),
                array('pass' => true, 'message' => 'submit invalid credentials (Auth/SubmitLoginAndFailCept)'),
                array('pass' => true, 'message' => 'submit valid credentials and see the dashboard (Auth/SubmitLoginAndSucceedCept)'),
            ),
            $result
        );

        $this->assertEquals(0, $parser->getTotalFailures());

    }
}
