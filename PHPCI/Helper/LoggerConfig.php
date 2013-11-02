<?php


namespace PHPCI\Helper;


use Monolog\Logger;

class LoggerConfig {

    private $config;

    /**
     * The file specified is expected to return an array. Where each key
     * is the name of a logger. The value of each key should be an array or
     * a function that returns an array of LogHandlers.
     * @param $logConfigFilePath
     */
    function __construct($logConfigFilePath) {
        if (file_exists($logConfigFilePath)) {
            $this->config = require_once($logConfigFilePath);
        }
        else {
            $this->config = array();
        }
    }

    /**
     * Returns an instance of Monolog with all configured handlers
     * added. The Monolog instance will be given $name.
     * @param $name
     * @return Logger
     */
    public function GetFor($name) {
        $handlers = array();

        // They key is expected to either be an array or
        // a callable function that returns an array
        if (isset($this->config[$name])) {
            if (is_callable($this->config[$name])) {
                $handlers = call_user_func($this->config[$name]);
            }
            elseif(is_array($this->config[$name])) {
                $handlers = $this->config[$name];
            }
        }

        return new Logger($name, $handlers);
    }

}