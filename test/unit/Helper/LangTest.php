<?php

/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\Kiboko\Component\ContinuousIntegration\Helper;

use DateTime;
use Kiboko\Component\ContinuousIntegration\Helper\Lang;

class LangTest extends \PHPUnit_Framework_TestCase
{
    public function testLang_UsePassedParameters()
    {
        $dateTime = $this->prophesize('DateTime');
        $dateTime->format(DateTime::ISO8601)->willReturn("ISODATE");
        $dateTime->format(DateTime::RFC2822)->willReturn("RFCDATE");

        $this->assertEquals('<time datetime="ISODATE" data-format="FORMAT">RFCDATE</time>', Lang::formatDateTime($dateTime->reveal(), 'FORMAT'));
    }

    public function testLang_UseDefaultFormat()
    {
        $dateTime = $this->prophesize('DateTime');
        $dateTime->format(DateTime::ISO8601)->willReturn("ISODATE");
        $dateTime->format(DateTime::RFC2822)->willReturn("RFCDATE");

        $this->assertEquals('<time datetime="ISODATE" data-format="lll">RFCDATE</time>', Lang::formatDateTime($dateTime->reveal()));
    }
}
