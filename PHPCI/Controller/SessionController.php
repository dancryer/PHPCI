<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Controller;

use b8;
use PHPCI\Helper\Email;

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
                header('Location: ' . $this->getLoginRedirect());
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

    public function forgotPassword()
    {
        if ($this->request->getMethod() == 'POST') {
            $email = $this->getParam('email', null);
            $user = $this->userStore->getByEmail($email);

            if (empty($user)) {
                $this->view->error = 'No user exists with that email address, please try again.';
                return $this->view->render();
            }

            $key = md5(date('Y-m-d') . $user->getHash());
            $url = PHPCI_URL;
            $name = $user->getName();
            $userId = $user->getId();

            $message = <<<MSG
Hi {$name},

You have received this email because you, or someone else, has requested a password reset for PHPCI.

If this was you, please click the following link to reset your password: {$url}session/reset-password/{$userId}/{$key}

Otherwise, please ignore this email and no action will be taken.

Thank you,

PHPCI
MSG;


            $email = new Email();
            $email->setEmailTo($user->getEmail(), $user->getName());
            $email->setSubject('Password reset');
            $email->setBody($message);
            $email->send();

            $this->view->emailed = true;
        }

        return $this->view->render();
    }

    public function resetPassword($userId, $key)
    {
        $user = $this->userStore->getById($userId);
        $userKey = md5(date('Y-m-d') . $user->getHash());

        if (empty($user) || $key != $userKey) {
            $this->view->error = 'Invalid password reset request.';
            return $this->view->render();
        }

        if ($this->request->getMethod() == 'POST') {
            $hash = password_hash($this->getParam('password'), PASSWORD_DEFAULT);
            $user->setHash($hash);

            $_SESSION['user'] = $this->userStore->save($user);
            $_SESSION['user_id'] = $user->getId();

            header('Location: ' . PHPCI_URL);
            die;
        }

        $this->view->id = $userId;
        $this->view->key = $key;

        return $this->view->render();
    }

    protected function getLoginRedirect()
    {
        $rtn = PHPCI_URL;

        if (!empty($_SESSION['login_redirect'])) {
            $rtn .= $_SESSION['login_redirect'];
            $_SESSION['login_redirect'] = null;
        }

        return $rtn;
    }
}
