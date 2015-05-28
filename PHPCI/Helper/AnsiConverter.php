<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Helper;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

/**
 * Converts ANSI output to HTML.
 *
 * @package PHPCI\Helper
 */
final class AnsiConverter
{
    static private $converter = null;

    /**
     * Initialize the singletion.
     *
     * @return AnsiToHtmlConverter
     */
    private static function getInstance()
    {
        if (self::$converter === null) {
            self::$converter = new AnsiToHtmlConverter(null, false);
        }

        return self::$converter;
    }

    /**
     * Convert a text containing ANSI colr sequences into HTML code.
     *
     * @param string $text The text to convert
     *
     * @return string The HTML code.
     */
    public static function convert($text)
    {
        return self::getInstance()->convert($text);
    }

    /**
     * Do not instanciate this class.
     */
    private function __construct()
    {
    }
}
