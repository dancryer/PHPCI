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
use b8\Http\Response\RedirectResponse;
use b8\View;

/**
* PHPCI Front Controller
* @author   Dan Cryer <dan@block8.co.uk>
*/
class Application extends b8\Application
{
    /**
    * Handle an incoming web request.
    */
    public function handleRequest()
    {
        // Registry legacy:
        $registry = new b8\Registry($this->config, $this->request);

        $this->initRequest();

        // Validate the user's session unless it is a login/logout action or a web hook:
        $sessionAction = ($this->controllerName == 'Session' && in_array($this->action, array('login', 'logout')));
        $externalAction = in_array($this->controllerName, array('Bitbucket', 'Github', 'BuildStatus'));
        $skipValidation = ($externalAction || $sessionAction);
        
        if($skipValidation || $this->validateSession()) {
            parent::handleRequest();
        }

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

        if ($this->request->isAjax()) {
            $this->response->setResponseCode(401);
            $this->response->setContent('');
        } else {
            $this->response = new RedirectResponse($this->response);
            $this->response->setHeader('Location', '/session/login');
        }

        return false;
    }
}
