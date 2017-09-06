<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2014, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         https://www.phptesting.org/
*/

namespace PHPCI;

use PHPCI\Framework;
use PHPCI\Framework\Exception\HttpException;
use PHPCI\Framework\Exception\HttpException\NotFoundException;
use PHPCI\Framework\Http;
use PHPCI\Framework\Http\Response;
use PHPCI\Framework\Http\Response\RedirectResponse;
use PHPCI\Framework\View;
use PHPCI\Model\User;
use PHPCI\Store\ProjectGroupStore;
use PHPCI\Store\ProjectStore;
use PHPCI\Store\UserStore;

/**
* PHPCI Front Controller
* @author   Dan Cryer <dan@block8.co.uk>
*/
class Application
{
    /**
     * @var User
     */
    public static $user;

    /**
     * @var array
     */
    protected $route;

    /**
     * @var \PHPCI\Controller
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

    /**
     * @var Http\Router
     */
    protected $router;

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
        try {
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

            $this->response = $this->getController()->handleAction($action, $this->route['args']);
        } catch (HttpException $ex) {
            $this->config->set('page_title', 'Error');

            $view = new View('exception');
            $view->exception = $ex;

            $this->response->setResponseCode($ex->getErrorCode());
            $this->response->setContent($view->render());
        } catch (\Exception $ex) {
            $this->config->set('page_title', 'Error');

            $view = new View('exception');
            $view->exception = $ex;

            $this->response->setResponseCode(500);
            $this->response->setContent($view->render());
        }

        if ($this->response->hasLayout() && $this->controller->layout) {
            $this->setLayoutVariables($this->controller->layout);

            $this->controller->layout->content  = $this->response->getContent();
            $this->response->setContent($this->controller->layout->render());
        }

        return $this->response;
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
        /** @var \PHPCI\Controller $controller */
        $controller = new $class($this->config, $this->request, $this->response, self::$user);
        $controller->init();

        $controller->layout = new View('layout');
        $controller->layout->title = 'PHPCI';
        $controller->layout->breadcrumb = [];

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

    /**
     * Initialise PHPCI - Handles session verification, routing, etc.
     */
    public function init()
    {
        $request =& $this->request;
        $route = '/:controller/:action';
        $opts = ['controller' => 'Home', 'action' => 'index'];

        // Inlined as a closure to fix "using $this when not in object context" on 5.3
        $validateSession = function () {
            if (!empty($_SESSION['phpci_user_id'])) {

                $user = UserStore::load()->getById($_SESSION['phpci_user_id']);

                if ($user) {
                    self::$user = $user;
                    return true;
                }

                unset($_SESSION['phpci_user_id']);
            }

            return false;
        };

        $skipAuth = [$this, 'shouldSkipAuth'];

        // Handler for the route we're about to register, checks for a valid session where necessary:
        $routeHandler = function (&$route, Response &$response) use (&$request, $validateSession, $skipAuth) {
            $skipValidation = in_array($route['controller'], ['session', 'webhook', 'build-status']);

            if (!$skipValidation && !$validateSession() && (!is_callable($skipAuth) || !$skipAuth())) {
                if ($request->isAjax()) {
                    $response->setResponseCode(401);
                    $response->setContent('');
                } else {
                    $_SESSION['phpci_login_redirect'] = substr($request->getPath(), 1);
                    $response = new RedirectResponse($response);
                    $response->setHeader('Location', PHPCI_URL.'session/login');
                }

                return false;
            }

            return true;
        };

        $this->router->clearRoutes();
        $this->router->register($route, $opts, $routeHandler);
    }

    /**
     * Injects variables into the layout before rendering it.
     * @param View $layout
     */
    protected function setLayoutVariables(View &$layout)
    {
        $groups = [];
        $groupStore = ProjectGroupStore::load();
        $groupList = $groupStore->find()->order('title', 'ASC')->get(100);

        foreach ($groupList as $group) {
            $thisGroup = ['title' => $group->getTitle()];
            $projects = ProjectStore::load()->getByGroupId($group->getId());
            $thisGroup['projects'] = $projects;
            $groups[] = $thisGroup;
        }

        $layout->groups = $groups;
    }

    /**
     * Check whether we should skip auth (because it is disabled)
     * @return bool
     */
    protected function shouldSkipAuth()
    {
        $config = Config::getInstance();
        $state = (bool)$config->get('phpci.authentication_settings.state', false);
        $userId    = $config->get('phpci.authentication_settings.user_id', 0);

        if (false !== $state && 0 != (int)$userId) {
            $userStore = UserStore::load();
            $user = $userStore->getById($userId);

            if ($user) {
                self::$user = $user;
                return true;
            }
        }

        return false;
    }
}
