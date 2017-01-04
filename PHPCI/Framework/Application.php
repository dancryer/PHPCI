<?php

namespace PHPCI\Framework;

use PHPCI\Config;
use PHPCI\Framework\Exception\HttpException\NotFoundException;
use PHPCI\Framework\Http;
use PHPCI\Framework\View;

class Application
{
    /**
     * @var array
     */
    protected $route;

    /**
     * @var \PHPCI\Framework\Controller
     */
    protected $controller;

    /**
    * @var \PHPCI\Framework\Http\Request
    */
    protected $request;

    /**
    * @var \PHPCI\Framework\Http\Response
    */
    protected $response;

    /**
    * @var \PHPCI\Config
    */
    protected $config;

    public function __construct(Config $config, Http\Request $request = null, Http\Response $response = null)
    {
        $this->config = $config;
        $this->response = is_null($response) ? new Http\Response() : $response;
        $this->request = is_null($request) ? new Http\Request() : $request;

        if (!is_null($request)) {
            $this->request = $request;
        } else {
            $this->request = new Http\Request();
        }

        $this->router = new Http\Router($this, $this->request, $this->config);

        if (method_exists($this, 'init')) {
            $this->init();
        }
    }

    public function handleRequest()
    {
        $this->route = $this->router->dispatch();

        if (!empty($this->route['callback'])) {
            $callback = $this->route['callback'];

            if (!$callback($this->route, $this->response)) {
                return $this->response;
            }
        }

        $action = lcfirst($this->toPhpName($this->route['action']));

        if (!$this->getController()->hasAction($action)) {
            throw new NotFoundException('Controller ' . $this->toPhpName($this->route['controller']) . ' does not have action ' . $action);
        }

        return $this->getController()->handleAction($action, $this->route['args']);
    }

    /**
     * @return \PHPCI\Controller
     */
    public function getController()
    {
        if (empty($this->controller)) {
            $namespace = $this->toPhpName($this->route['namespace']);
            $controller = $this->toPhpName($this->route['controller']);
            $controllerClass = 'PHPCI\\' . $namespace . '\\' . $controller . 'Controller';
            $this->controller = $this->loadController($controllerClass);
        }

        return $this->controller;
    }

    protected function loadController($class)
    {
        $controller = new $class($this->config, $this->request, $this->response);
        $controller->init();

        return $controller;
    }

    protected function controllerExists($route)
    {
        $namespace = $this->toPhpName($route['namespace']);
        $controller = $this->toPhpName($route['controller']);

        $controllerClass = 'PHPCI\\' . $namespace . '\\' . $controller . 'Controller';

        return class_exists($controllerClass);
    }

    public function isValidRoute($route)
    {
        if ($this->controllerExists($route)) {
            return true;
        }

        return false;
    }

    protected function toPhpName($string)
    {
        $string = str_replace('-', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);

        return $string;
    }
}
