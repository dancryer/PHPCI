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
class SessionController extends \PHPCI\Controller
{
    /**
     * @var \PHPCI\Store\UserStore
     */
    protected $userStore;

    public function init()
    {
        $this->response->disableLayout();
        $this->userStore       = b8\Store\Factory::getStore('User');
    }

    /**
    * Handles user login (form and processing)
    */
    public function login()
    {
        $isLoginFailure = false;

        if ($this->request->getMethod() == 'POST') {
            $user = $this->userStore->getByEmail($this->getParam('email'));
            
            if ($user && password_verify($this->getParam('password', ''), $user->getHash())) {
                $_SESSION['user_id']    = $user->getId();
                header('Location: ' . PHPCI_URL);
                die;
            } else {
                $isLoginFailure = true;
            }
        }

        $form = new b8\Form();
        $form->setMethod('POST');
        $form->setAction(PHPCI_URL.'session/login');

        $email = new b8\Form\Element\Email('email');
        $email->setLabel('Email Address');
        $email->setRequired(true);
        $email->setContainerClass('form-group');
        $email->setClass('form-control');
        $form->addField($email);

        $pwd = new b8\Form\Element\Password('password');
        $pwd->setLabel('Password');
        $pwd->setRequired(true);
        $pwd->setContainerClass('form-group');
        $pwd->setClass('form-control');
        $form->addField($pwd);

        $pwd = new b8\Form\Element\Submit();
        $pwd->setValue('Log in &raquo;');
        $pwd->setClass('btn-success');
        $form->addField($pwd);

        $this->view->form = $form->render();
        $this->view->failed = $isLoginFailure;
        
        return $this->view->render();
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
