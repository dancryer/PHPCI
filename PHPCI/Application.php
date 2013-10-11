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
        try {
            $this->initRequest();

            // Validate the user's session unless it is a login/logout action or a web hook:
            $sessionAction = ($this->controllerName == 'Session' && in_array($this->action, array('login', 'logout')));
            $externalAction = in_array($this->controllerName, array('Bitbucket', 'Github', 'Gitlab', 'BuildStatus'));
            $skipValidation = ($externalAction || $sessionAction);

            if ($skipValidation || $this->validateSession()) {
                parent::handleRequest();
            }
        } catch (\Exception $ex) {
            $content = '<h1>There was a problem with this request</h1>
            <p>Please paste the details below into a
            <a href="https://github.com/Block8/PHPCI/issues/new">new bug report</a>
            so that we can investigate and fix it.</p>';

            ob_start();
            var_dump(array(
                'message' => $ex->getMessage(),
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'trace' => $ex->getTraceAsString()
            ));
            var_dump(array(
                'PATH_INFO' => $_SERVER['PATH_INFO'],
                'REDIRECT_PATH_INFO' => $_SERVER['REDIRECT_PATH_INFO'],
                'REQUEST_URI' => $_SERVER['REQUEST_URI'],
                'PHP_SELF' => $_SERVER['PHP_SELF'],
                'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'],
                'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'],
                'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME'],
                'SERVER_SOFTWARE' => $_SERVER['SERVER_SOFTWARE'],
            ));
            $content .= ob_get_contents();
            ob_end_clean();

            $this->response->setContent($content);
            $this->response->disableLayout();
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
            $this->response->setHeader('Location', PHPCI_URL.'session/login');
        }

        return false;
    }
}
