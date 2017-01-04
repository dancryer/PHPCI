<?php

namespace PHPCI\Framework\Http;

use PHPCI\Framework\Application;
use PHPCI\Config;
use PHPCI\Framework\Http\Request;

class Router
{
    /**
     * @var \PHPCI\Framework\Http\Request;
     */
    protected $request;

    /**
     * @var \PHPCI\Framework\Http\Config;
     */
    protected $config;

    /**
     * @var \PHPCI\Framework\Application
     */
    protected $application;

    /**
     * @var array
     */
    protected $routes = array(array('route' => '/:controller/:action', 'callback' => null, 'defaults' => array()));

    public function __construct(Application $application, Request $request, Config $config)
    {
        $this->application = $application;
        $this->request = $request;
        $this->config = $config;
    }

    public function clearRoutes()
    {
        $this->routes = array();
    }

    /**
     * @param string $route Route definition
     * @param array $options
     * @param callable $callback
     * @throws \InvalidArgumentException
     */
    public function register($route, $options = array(), $callback = null)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('$callback must be callable.');
        }

        array_unshift($this->routes, array('route' => $route, 'callback' => $callback, 'defaults' => $options));
    }

    public function dispatch()
    {
        foreach ($this->routes as $route) {
            $pathParts = $this->request->getPathParts();

            //-------
            // Set up default values for everything:
            //-------
            $thisNamespace = 'Controller';
            $thisController = null;
            $thisAction = null;

            if (array_key_exists('namespace', $route['defaults'])) {
                $thisNamespace = $route['defaults']['namespace'];
            }

            if (array_key_exists('controller', $route['defaults'])) {
                $thisController = $route['defaults']['controller'];
            }

            if (array_key_exists('action', $route['defaults'])) {
                $thisAction = $route['defaults']['action'];
            }

            $routeParts = array_filter(explode('/', $route['route']));
            $routeMatches = true;

            while (count($routeParts)) {
                $routePart = array_shift($routeParts);
                $pathPart = array_shift($pathParts);

                switch ($routePart) {
                    case ':namespace':
                        $thisNamespace = !is_null($pathPart) ? $pathPart : $thisNamespace;
                        break;
                    case ':controller':
                        $thisController = !is_null($pathPart) ? $pathPart : $thisController;
                        break;
                    case ':action':
                        $thisAction = !is_null($pathPart) ? $pathPart : $thisAction;
                        break;
                    default:
                        if ($routePart != $pathPart) {
                            $routeMatches = false;
                        }
                }

                if (!$routeMatches || !count($pathParts)) {
                    break;
                }
            }

            $thisArgs = $pathParts;

            if ($routeMatches) {
                $route = array('namespace' => $thisNamespace, 'controller' => $thisController, 'action' => $thisAction, 'args' => $thisArgs, 'callback' => $route['callback']);

                if ($this->application->isValidRoute($route)) {
                    return $route;
                }
            }
        }

        return null;
    }
}