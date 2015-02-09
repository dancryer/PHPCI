<?php

namespace PHPCI\Tests\Helper;

use DateTime;
use PHPCI\Helper\Lang;
use Prophecy\PhpUnit\ProphecyTestCase;

class LangTest extends ProphecyTestCase
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
