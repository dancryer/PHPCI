<?php

namespace PHPCI\Helper\Tests;

use PHPCI\Helper\AnsiConverter;
use PHPUnit_Framework_TestCase;

class AnsiConverterTest extends PHPUnit_Framework_TestCase
{
    public function testConvert_convertToHtml()
    {
        $input = "\e[31mThis is red !\e[0m";

        $expectedOutput = '<span class="ansi_color_bg_black ansi_color_fg_red">This is red !</span>';

        $actualOutput = AnsiConverter::convert($input);

        $this->assertEquals($expectedOutput, $actualOutput);
    }
}
