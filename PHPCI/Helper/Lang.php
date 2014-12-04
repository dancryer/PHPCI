<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Helper;

use b8\Config;
use PHPCI\Model\User;

class Lang
{
    protected static $language = null;
    protected static $strings = array();

    public static function get($string)
    {
        $vars = func_get_args();

        if (array_key_exists($string, self::$strings)) {
            $vars[0] = self::$strings[$string];
            return call_user_func_array('sprintf', $vars);
        }

        return '%%MISSING STRING: ' . $string . '%%';
    }

    public static function getStrings()
    {
        return self::$strings;
    }

    public static function init(Config $config)
    {
        self::$language = $config->get('phpci.default_language', 'en');
        self::$strings = self::loadLanguage();

        if (is_null(self::$strings)) {
            self::$language = 'en';
            self::$strings = self::loadLanguage();
        }
    }

    protected static function loadLanguage()
    {
        $langFile = PHPCI_DIR . 'PHPCI/Languages/lang.' . self::$language . '.php';

        if (!file_exists($langFile)) {
            return null;
        }

        require_once($langFile);

        if (is_null($strings) || !is_array($strings) || !count($strings)) {
            return null;
        }

        return $strings;
    }
}