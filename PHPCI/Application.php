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

        $this->router->clearRoutes();
        $this->router->register($route, $opts, function (&$route, Response &$response) use (&$request) {
            $skipValidation = in_array($route['controller'], array('session', 'webhook', 'build-status'));

            if (!$skipValidation && !$this->validateSession()) {
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
        });
    }
    /**
    * Handle an incoming web request.
    */
    public function handleRequest()
    {
        $this->response = parent::handleRequest();

        if (View::exists('layout') && $this->response->hasLayout()) {
            $view           = new View('layout');
            $view->content  = $this->response->getContent();
            $this->response->setContent($view->render());
        }

        return $this->response;
    }

    /**
    * Validate whether or not the remote user has a valid session:
    */
    protected function validateSession()
    {
        if (!empty($_SESSION['user_id'])) {
            $user = b8\Store\Factory::getStore('User')->getByPrimaryKey($_SESSION['user_id']);

            if ($user) {
                $_SESSION['user'] = $user;
                return true;
            }

            unset($_SESSION['user_id']);
        }

        return false;
    }
}
