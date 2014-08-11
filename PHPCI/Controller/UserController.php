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
use b8\Exception\HttpException\ForbiddenException;
use b8\Exception\HttpException\NotFoundException;
use b8\Form;
use PHPCI\Controller;
use PHPCI\Model\User;
use PHPCI\Service\UserService;

/**
* User Controller - Allows an administrator to view, add, edit and delete users.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class UserController extends Controller
{
    /**
     * @var \PHPCI\Store\UserStore
     */
    protected $userStore;

    /**
     * @var \PHPCI\Service\UserService
     */
    protected $userService;

    public function init()
    {
        $this->userStore = b8\Store\Factory::getStore('User');
        $this->userService = new UserService($this->userStore);
    }

    /**
    * View user list.
    */
    public function index()
    {
        $users          = $this->userStore->getWhere(array(), 1000, 0, array(), array('email' => 'ASC'));
        $this->view->users    = $users;

        $this->config->set('page_title', 'Users');

        return $this->view->render();
    }

    public function profile()
    {
        $user = $_SESSION['user'];
        $values = $user->getDataArray();

        if ($this->request->getMethod() == 'POST') {
            $name = $this->getParam('name', null);
            $email = $this->getParam('email', null);
            $password = $this->getParam('password', null);

            $_SESSION['user'] = $this->userService->updateUser($name, $email, $password);
        }

        $form = new Form();
        $form->setAction(PHPCI_URL.'user/profile');
        $form->setMethod('POST');

        $name = new Form\Element\Text('name');
        $name->setClass('form-control');
        $name->setContainerClass('form-group');
        $name->setLabel('Name');
        $name->setRequired(true);
        $form->addField($name);

        $email = new Form\Element\Email('email');
        $email->setClass('form-control');
        $email->setContainerClass('form-group');
        $email->setLabel('Email Address');
        $email->setRequired(true);
        $form->addField($email);

        $password = new Form\Element\Password('password');
        $password->setClass('form-control');
        $password->setContainerClass('form-group');
        $password->setLabel('Password (leave blank if you don\'t want to change it)');
        $password->setRequired(false);
        $form->addField($password);

        $submit = new Form\Element\Submit();
        $submit->setClass('btn btn-success');
        $submit->setValue('Save &raquo;');
        $form->addField($submit);

        $form->setValues($values);

        $this->view->form = $form;

        return $this->view->render();
    }

    /**
    * Add a user - handles both form and processing.
    */
    public function add()
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new ForbiddenException('You do not have permission to do that.');
        }

        $this->config->set('page_title', 'Add User');

        $method = $this->request->getMethod();

        if ($method == 'POST') {
            $values = $this->getParams();
        } else {
            $values = array();
        }

        $form   = $this->userForm($values);

        if ($method != 'POST' || ($method == 'POST' && !$form->validate())) {
            $view           = new b8\View('UserForm');
            $view->type     = 'add';
            $view->user     = null;
            $view->form     = $form;

            return $view->render();
        }


        $name = $this->getParam('name', null);
        $email = $this->getParam('email', null);
        $password = $this->getParam('password', null);
        $isAdmin = (int)$this->getParam('is_admin', 0);

        $this->userService->createUser($name, $email, $password, $isAdmin);

        header('Location: '.PHPCI_URL.'user');
        die;
    }

    /**
    * Edit a user - handles both form and processing.
    */
    public function edit($userId)
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new ForbiddenException('You do not have permission to do that.');
        }

        $method = $this->request->getMethod();
        $user = $this->userStore->getById($userId);

        if (empty($user)) {
            throw new NotFoundException('User with ID: ' . $userId . ' does not exist.');
        }

        $values = array_merge($user->getDataArray(), $this->getParams());
        $form = $this->userForm($values, 'edit/' . $userId);

        if ($method != 'POST' || ($method == 'POST' && !$form->validate())) {
            $view = new b8\View('UserForm');
            $view->type = 'edit';
            $view->user = $user;
            $view->form = $form;

            return $view->render();
        }

        $name = $this->getParam('name', null);
        $email = $this->getParam('email', null);
        $password = $this->getParam('password', null);
        $isAdmin = (int)$this->getParam('is_admin', 0);

        $this->userService->updateUser($user, $name, $email, $password, $isAdmin);

        header('Location: '.PHPCI_URL.'user');
        die;
    }

    /**
    * Create user add / edit form.
    */
    protected function userForm($values, $type = 'add')
    {
        $form = new Form();
        $form->setMethod('POST');
        $form->setAction(PHPCI_URL.'user/' . $type);
        $form->addField(new Form\Element\Csrf('csrf'));

        $field = new Form\Element\Email('email');
        $field->setRequired(true);
        $field->setLabel('Email Address');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Text('name');
        $field->setRequired(true);
        $field->setLabel('Name');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Password('password');

        if ($type == 'add') {
            $field->setRequired(true);
            $field->setLabel('Password');
        } else {
            $field->setRequired(false);
            $field->setLabel('Password (leave blank to keep current password)');
        }

        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Checkbox('is_admin');
        $field->setRequired(false);
        $field->setCheckedValue(1);
        $field->setLabel('Is this user an administrator?');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue('Save User');
        $field->setClass('btn-success');
        $form->addField($field);

        $form->setValues($values);
        return $form;
    }

    /**
    * Delete a user.
    */
    public function delete($userId)
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new ForbiddenException('You do not have permission to do that.');
        }
        
        $user   = $this->userStore->getById($userId);

        if (empty($user)) {
            throw new NotFoundException('User with ID: ' . $userId . ' does not exist.');
        }

        $this->userService->deleteUser($user);

        header('Location: '.PHPCI_URL.'user');
        die;
    }
}
