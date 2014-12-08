<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Logging;

use Monolog\Logger;

/**
 * Class LoggerConfig
 * @package PHPCI\Logging
 */
class LoggerConfig
{
    const KEY_ALWAYS_LOADED = "_";
    private $config;

    /**
     * The filepath is expected to return an array which will be
     * passed to the normal constructor.
     *
     * @param string $filePath
     * @return LoggerConfig
     */
    public static function newFromFile($filePath)
    {
        if (file_exists($filePath)) {
            $configArray = require($filePath);
        } else {
            $configArray = array();
        }
        return new self($configArray);
    }

    /**
     * Each key of the array is the name of a logger. The value of
     * each key should be an array or a function that returns an
     * array of LogHandlers.
     * @param array $configArray
     */
    public function __construct(array $configArray = array())
    {
        $this->config = $configArray;
    }

    /**
     * Returns an instance of Monolog with all configured handlers
     * added. The Monolog instance will be given $name.
     * @param $name
     * @return Logger
     */
    public function getFor($name)
    {
        $handlers = $this->getHandlers(self::KEY_ALWAYS_LOADED);
        $handlers = array_merge($handlers, $this->getHandlers($name));
        return new Logger($name, $handlers);
    }

    /**
     * Return an array of enabled log handlers.
     * @param $key
     * @return array|mixed
     */
    protected function getHandlers($key)
    {
        $handlers = array();

        // They key is expected to either be an array or
        // a callable function that returns an array
        if (isset($this->config[$key])) {
            if (is_callable($this->config[$key])) {
                $handlers = call_user_func($this->config[$key]);
            } elseif (is_array($this->config[$key])) {
                $handlers = $this->config[$key];
            }
        }
        return $handlers;
    }
}
