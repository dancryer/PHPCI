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
use b8\Registry;

/**
* PHPCI Front Controller
* @author   Dan Cryer <dan@block8.co.uk>
*/
class Application extends b8\Application
{
    public function handleRequest()
    {
        $controllerName = \b8\Registry::getInstance()->get('ControllerName');
        $sessionAction = ($controllerName == 'Session' && in_array($this->action, array('login', 'logout')));
        $webhookAction = in_array($controllerName, array('Bitbucket', 'Github'));

        if (!$webhookAction && !$sessionAction) {
            $this->validateSession();
        }

        $view           = new b8\View('Layout');
        $view->content  = parent::handleRequest();

        return $view->render();
    }

    protected function validateSession()
    {
        if (!empty($_SESSION['user_id'])) {
            $user = b8\Store\Factory::getStore('User')->getByPrimaryKey($_SESSION['user_id']);

            if ($user) {
                Registry::getInstance()->set('user', $user);
                return;
            }

            unset($_SESSION['user_id']);
        }

        header('Location: /session/login');
        die;
    }
}
