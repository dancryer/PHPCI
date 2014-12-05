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

    public static function out()
    {
        print call_user_func_array(array('PHPCI\Helper\Lang', 'get'), func_get_args());
    }

    public static function getStrings()
    {
        return self::$strings;
    }

    public static function init(Config $config)
    {
        // Try user language:
        if (isset($_SERVER) && array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {
            $matches = array();

            if (preg_match('/([a-zA-Z]{2}\-[a-zA-Z]{2})/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches)) {
                self::$language = strtolower($matches[1]);
                self::$strings = self::loadLanguage();

                if (!is_null(self::$strings)) {
                    return;
                }
            }
        }

        // Try the installation default language:
        self::$language = $config->get('phpci.default_language', null);

        if (!is_null(self::$language)) {
            self::$strings = self::loadLanguage();

            if (!is_null(self::$strings)) {
                return;
            }
        }

        // Fall back to en-GB:
        self::$language = 'en-gb';
        self::$strings = self::loadLanguage();
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
