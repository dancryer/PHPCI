<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Helper;

class Session {
    const PREFIX = 'PHPCI_';

    public static function set($offset, $value) {
        $_SESSION[self::PREFIX . $offset] = $value;
    }

    public static function exists($offset) {
        return isset($_SESSION[self::PREFIX . $offset]);
    }

    public static function remove($offset) {
        unset($_SESSION[self::PREFIX . $offset]);
    }

    public static function get($offset) {
        return isset($_SESSION[self::PREFIX . $offset]) ? $_SESSION[self::PREFIX . $offset] : null;
    }
}
