<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Controller;

use b8;

/**
* Session Controller - Handles user login / logout.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class SessionController extends b8\Controller
{
    public function init()
    {
        $this->_userStore       = b8\Store\Factory::getStore('User');
    }

    /**
    * Handles user login (form and processing)
    */
    public function login()
    {
        if (b8\Registry::getInstance()->get('requestMethod') == 'POST') {
            $user = $this->_userStore->getByEmail($this->getParam('email'));

            if ($user && password_verify($this->getParam('password', ''), $user->getHash())) {
                $_SESSION['user_id']    = $user->getId();
                header('Location: ' . PHPCI_URL);
                die;
            }
        }

        $form = new b8\Form();
        $form->setMethod('POST');
        $form->setAction('/session/login');

        $email = new b8\Form\Element\Email('email');
        $email->setLabel('Email Address');
        $email->setRequired(true);
        $email->setClass('span3');
        $form->addField($email);

        $pwd = new b8\Form\Element\Password('password');
        $pwd->setLabel('Password');
        $pwd->setRequired(true);
        $pwd->setClass('span3');
        $form->addField($pwd);

        $pwd = new b8\Form\Element\Submit();
        $pwd->setValue('Login &raquo;');
        $pwd->setClass('btn-success');
        $form->addField($pwd);

        $view = new b8\View('Login');
        $view->form = $form->render();
        die($view->render());
    }

    /**
    * Handles user logout.
    */
    public function logout()
    {
        $_SESSION = array();
        session_destroy();
        header('Location: ' . PHPCI_URL);
        die;
    }
}
