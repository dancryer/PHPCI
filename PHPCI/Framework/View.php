<?php

namespace PHPCI\Framework;

use PHPCI\Config;
use PHPCI\Framework\Exception\HttpException;

class View
{
    protected $_vars = [];
    protected static $_helpers = [];
    protected static $extension = 'phtml';

    public function __construct($file, $path = null)
    {
        if (!self::exists($file, $path)) {
            throw new \Exception('View file does not exist: ' . $file);
        }

        $this->viewFile = self::getViewFile($file, $path);
    }

    protected static function getViewFile($file, $path = null)
    {
        $viewPath = is_null($path) ? Config::getInstance()->get('b8.view.path') : $path;
        $fullPath = $viewPath . $file . '.' . static::$extension;

        return $fullPath;
    }

    public static function exists($file, $path = null)
    {
        if (!file_exists(self::getViewFile($file, $path))) {
            return false;
        }

        return true;
    }

    public function __isset($var)
    {
        return isset($this->_vars[$var]);
    }

    public function __get($var)
    {
        return $this->_vars[$var];
    }

    public function __set($var, $val)
    {
        $this->_vars[$var] = $val;
    }

    public function __call($method, $params = [])
    {
        if (!isset(self::$_helpers[$method])) {
            $class = '\\PHPCI\\Helper\\' . $method;

            if (!class_exists($class)) {
                $class = '\\PHPCI\\Framework\\View\\Helper\\' . $method;
            }

            if (!class_exists($class)) {
                throw new HttpException('Helper class does not exist: ' . $class);
            }

            self::$_helpers[$method] = new $class();
        }

        return self::$_helpers[$method];
    }

    public function render()
    {
        extract($this->_vars);

        ob_start();
        require($this->viewFile);
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}