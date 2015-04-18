<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Helper;

use DateTime;
use PHPCI\Helper\Lang;

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
