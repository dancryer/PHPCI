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
    /**
    * Handle an incoming web request.
    */
    public function handleRequest()
    {
        $controllerName = \b8\Registry::getInstance()->get('ControllerName');

        // Validate the user's session unless it is a login/logout action or a web hook:
        $sessionAction = ($controllerName == 'Session' && in_array($this->action, array('login', 'logout')));
        $externalAction = in_array($controllerName, array('Bitbucket', 'Github', 'BuildStatus'));

        if (!$externalAction && !$sessionAction) {
            $this->validateSession();
        }

        // Render content into layout and return:
        $view           = new b8\View('Layout');
        $view->content  = parent::handleRequest();

        return $view->render();
    }

    /**
    * Validate whether or not the remote user has a valid session:
    */
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
