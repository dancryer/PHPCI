<?php

namespace PHPCI\Framework;

use PHPCI\Config;
use PHPCI\Framework\Http\Request;

/**
 * PHPCI\Framework\Registry is now deprecated in favour of using the following classes:
 * @see \PHPCI\Framework\Http\Request
 * @see \PHPCI\Framework\Http\Response
 * @see \PHPCI\Config
 */
class Registry
{
    /**
     * @var \PHPCI\Framework\Registry
     */
    protected static $instance;
    protected $_data = [];
    protected $_params = null;

    /**
     * @var \PHPCI\Config
     */
    protected $config;

    /**
     * @var \PHPCI\Framework\Http\Request
     */
    protected $request;

    /**
     * @return Registry
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    public function __construct(Config $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;

        self::$instance = $this;
    }

    public function get($key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    public function set($key, $value)
    {
        return $this->config->set($key, $value);
    }

    public function setArray($array)
    {
        return $this->config->set($array);
    }

    public function getParams()
    {
        return $this->request->getParams();
    }

    public function getParam($key, $default)
    {
        return $this->request->getParam($key, $default);
    }

    public function setParam($key, $value)
    {
        return $this->request->setParam($key, $value);
    }

    public function unsetParam($key)
    {
        return $this->request->unsetParam($key);
    }

    public function parseInput()
    {
    }
}