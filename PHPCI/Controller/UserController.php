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
use b8\Registry;
use PHPCI\Model\User;
use b8\Form;

/**
* User Controller - Allows an administrator to view, add, edit and delete users.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class UserController extends  \PHPCI\Controller
{
    public function init()
    {
        $this->_userStore       = b8\Store\Factory::getStore('User');
    }

    /**
    * View user list.
    */
    public function index()
    {
        $users          = $this->_userStore->getWhere(array(), 1000, 0, array(), array('email' => 'ASC'));
        $this->view->users    = $users;

        return $this->view->render();
    }

    /**
    * Add a user - handles both form and processing.
    */
    public function add()
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new \Exception('You do not have permission to do that.');
        }

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

        $values             = $form->getValues();
        $values['is_admin'] = $values['admin'] ? 1 : 0;
        $values['hash']     = password_hash($values['password'], PASSWORD_DEFAULT);

        $user = new User();
        $user->setValues($values);

        $user = $this->_userStore->save($user);

        header('Location: '.PHPCI_URL.'user');
        die;
    }

    /**
    * Edit a user - handles both form and processing.
    */
    public function edit($userId)
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new \Exception('You do not have permission to do that.');
        }

        $method     = $this->request->getMethod();
        $user   = $this->_userStore->getById($userId);

        if ($method == 'POST') {
            $values = $this->getParams();
        } else {
            $values             = $user->getDataArray();
            $values['admin']    = $values['is_admin'];
        }

        $form   = $this->userForm($values, 'edit/' . $userId);

        if ($method != 'POST' || ($method == 'POST' && !$form->validate())) {
            $view           = new b8\View('UserForm');
            $view->type     = 'edit';
            $view->user     = $user;
            $view->form     = $form;

            return $view->render();
        }

        $values             = $form->getValues();
        $values['is_admin'] = $values['admin'] ? 1 : 0;

        if (!empty($values['password'])) {
            $values['hash'] = password_hash($values['password'], PASSWORD_DEFAULT);
        }

        $user->setValues($values);
        $user = $this->_userStore->save($user);

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
        $form->setAction('/user/' . $type);
        $form->addField(new Form\Element\Csrf('csrf'));

        $field = new Form\Element\Email('email');
        $field->setRequired(true);
        $field->setLabel('Email Address');
        $field->setClass('span4');
        $form->addField($field);

        $field = new Form\Element\Text('name');
        $field->setRequired(true);
        $field->setLabel('Name');
        $field->setClass('span4');
        $form->addField($field);

        $field = new Form\Element\Password('password');
        $field->setRequired(true);
        $field->setLabel('Password' . ($type == 'edit' ? ' (leave blank to keep current password)' : ''));
        $field->setClass('span4');
        $form->addField($field);

        $field = new Form\Element\Checkbox('admin');
        $field->setRequired(false);
        $field->setCheckedValue(1);
        $field->setLabel('Is this user an administrator?');
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
            throw new \Exception('You do not have permission to do that.');
        }
        
        $user   = $this->_userStore->getById($userId);
        $this->_userStore->delete($user);

        header('Location: '.PHPCI_URL.'user');
        die;
    }
}
