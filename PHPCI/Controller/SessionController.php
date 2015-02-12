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
use PHPCI\Helper\Lang;

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

    /**
     * Initialise the controller, set up stores and services.
     */
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
                $_SESSION['phpci_user_id']    = $user->getId();
                $response = new b8\Http\Response\RedirectResponse();
                $response->setHeader('Location', $this->getLoginRedirect());
                return $response;
            } else {
                $isLoginFailure = true;
            }
        }

        $form = new b8\Form();
        $form->setMethod('POST');
        $form->setAction(PHPCI_URL.'session/login');

        $email = new b8\Form\Element\Email('email');
        $email->setLabel(Lang::get('email_address'));
        $email->setRequired(true);
        $email->setContainerClass('form-group');
        $email->setClass('form-control');
        $form->addField($email);

        $pwd = new b8\Form\Element\Password('password');
        $pwd->setLabel(Lang::get('password'));
        $pwd->setRequired(true);
        $pwd->setContainerClass('form-group');
        $pwd->setClass('form-control');
        $form->addField($pwd);

        $pwd = new b8\Form\Element\Submit();
        $pwd->setValue(Lang::get('log_in'));
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
        unset($_SESSION['phpci_user']);
        unset($_SESSION['phpci_user_id']);

        session_destroy();

        $response = new b8\Http\Response\RedirectResponse();
        $response->setHeader('Location', PHPCI_URL);
        return $response;
    }

    /**
     * Allows the user to request a password reset email.
     * @return string
     */
    public function forgotPassword()
    {
        if ($this->request->getMethod() == 'POST') {
            $email = $this->getParam('email', null);
            $user = $this->userStore->getByEmail($email);

            if (empty($user)) {
                $this->view->error = Lang::get('reset_no_user_exists');
                return $this->view->render();
            }

            $key = md5(date('Y-m-d') . $user->getHash());
            $url = PHPCI_URL;

            $message = Lang::get('reset_email_body', $user->getName(), $url, $user->getId(), $key);

            $email = new Email();
            $email->setEmailTo($user->getEmail(), $user->getName());
            $email->setSubject(Lang::get('reset_email_title', $user->getName()));
            $email->setBody($message);
            $email->send();

            $this->view->emailed = true;
        }

        return $this->view->render();
    }

    /**
     * Allows the user to change their password after a password reset email.
     * @param $userId
     * @param $key
     * @return string
     */
    public function resetPassword($userId, $key)
    {
        $user = $this->userStore->getById($userId);
        $userKey = md5(date('Y-m-d') . $user->getHash());

        if (empty($user) || $key != $userKey) {
            $this->view->error = Lang::get('reset_invalid');
            return $this->view->render();
        }

        if ($this->request->getMethod() == 'POST') {
            $hash = password_hash($this->getParam('password'), PASSWORD_DEFAULT);
            $user->setHash($hash);

            $_SESSION['phpci_user'] = $this->userStore->save($user);
            $_SESSION['phpci_user_id'] = $user->getId();

            $response = new b8\Http\Response\RedirectResponse();
            $response->setHeader('Location', PHPCI_URL);
            return $response;
        }

        $this->view->id = $userId;
        $this->view->key = $key;

        return $this->view->render();
    }

    /**
     * Get the URL the user was trying to go to prior to being asked to log in.
     * @return string
     */
    protected function getLoginRedirect()
    {
        $rtn = PHPCI_URL;

        if (!empty($_SESSION['phpci_login_redirect'])) {
            $rtn .= $_SESSION['phpci_login_redirect'];
            $_SESSION['phpci_login_redirect'] = null;
        }

        return $rtn;
    }
}
