<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI;

use b8;
use b8\Http\Response;
use b8\Http\Response\RedirectResponse;
use b8\View;

/**
* PHPCI Front Controller
* @author   Dan Cryer <dan@block8.co.uk>
*/
class Application extends b8\Application
{
    public function init()
    {
        $request =& $this->request;
        $route = '/:controller/:action';
        $opts = array('controller' => 'Home', 'action' => 'index');

        // Inlined as a closure to fix "using $this when not in object context" on 5.3
        $validateSession = function () {
            if (!empty($_SESSION['user_id'])) {
                $user = b8\Store\Factory::getStore('User')->getByPrimaryKey($_SESSION['user_id']);

                if ($user) {
                    $_SESSION['user'] = $user;
                    return true;
                }

                unset($_SESSION['user_id']);
            }

            return false;
        };

        // Handler for the route we're about to register, checks for a valid session where necessary:
        $routeHandler = function (&$route, Response &$response) use (&$request, $validateSession) {
            $skipValidation = in_array($route['controller'], array('session', 'webhook', 'build-status'));

            if (!$skipValidation && !$validateSession()) {
                if ($request->isAjax()) {
                    $response->setResponseCode(401);
                    $response->setContent('');
                } else {
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
    */
    public function handleRequest()
    {
        $this->response = parent::handleRequest();

        if (View::exists('layout') && $this->response->hasLayout()) {
            $view           = new View('layout');
            $pageTitle = $this->config->get('page_title', null);

            if (!is_null($pageTitle)) {
                $view->title = $pageTitle;
            }

            $view->content  = $this->response->getContent();
            $this->response->setContent($view->render());
        }

        return $this->response;
    }
}
