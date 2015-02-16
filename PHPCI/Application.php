<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2014, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         https://www.phptesting.org/
*/

namespace PHPCI;

use b8;
use b8\Exception\HttpException;
use b8\Http\Response;
use b8\Http\Response\RedirectResponse;
use b8\View;

/**
* PHPCI Front Controller
* @author   Dan Cryer <dan@block8.co.uk>
*/
class Application extends b8\Application
{
    /**
     * @var \PHPCI\Controller
     */
    protected $controller;

    /**
     * Initialise PHPCI - Handles session verification, routing, etc.
     */
    public function init()
    {
        $request =& $this->request;
        $route = '/:controller/:action';
        $opts = array('controller' => 'Home', 'action' => 'index');

        // Inlined as a closure to fix "using $this when not in object context" on 5.3
        $validateSession = function () {
            if (!empty($_SESSION['phpci_user_id'])) {
                $user = b8\Store\Factory::getStore('User')->getByPrimaryKey($_SESSION['phpci_user_id']);

                if ($user) {
                    $_SESSION['phpci_user'] = $user;
                    return true;
                }

                unset($_SESSION['phpci_user_id']);
            }

            return false;
        };

        $skipAuth = [$this, 'shouldSkipAuth'];

        // Handler for the route we're about to register, checks for a valid session where necessary:
        $routeHandler = function (&$route, Response &$response) use (&$request, $validateSession, $skipAuth) {
            $skipValidation = in_array($route['controller'], array('session', 'webhook', 'build-status'));

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
     * Handle an incoming web request.
     *
     * @return b8\b8\Http\Response|Response
     */
    public function handleRequest()
    {
        try {
            $this->response = parent::handleRequest();
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
     * Loads a particular controller, and injects our layout view into it.
     * @param $class
     * @return mixed
     */
    protected function loadController($class)
    {
        $controller = parent::loadController($class);
        $controller->layout = new View('layout');
        $controller->layout->title = 'PHPCI';
        $controller->layout->breadcrumb = array();

        return $controller;
    }

    /**
     * Injects variables into the layout before rendering it.
     * @param View $layout
     */
    protected function setLayoutVariables(View &$layout)
    {
        /** @var \PHPCI\Store\ProjectStore $projectStore */
        $projectStore = b8\Store\Factory::getStore('Project');
        $layout->projects = $projectStore->getAll();
    }

    /**
     * Check whether we should skip auth (because it is disabled)
     * @return bool
     */
    protected function shouldSkipAuth()
    {
        $config = b8\Config::getInstance();
        $state = (bool)$config->get('phpci.authentication_settings.state', false);
        $userId    = $config->get('phpci.authentication_settings.user_id', 0);

        if (false !== $state && 0 != (int)$userId) {
            $user = b8\Store\Factory::getStore('User')
                ->getByPrimaryKey($userId);

            if ($user) {
                $_SESSION['phpci_user'] = $user;
                return true;
            }
        }

        return false;
    }
}
