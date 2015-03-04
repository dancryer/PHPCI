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
use b8\Exception\HttpException\NotFoundException;
use b8\Form;
use PHPCI\Controller;
use PHPCI\Helper\Lang;
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

    /**
     * Initialise the controller, set up stores and services.
     */
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

        $this->layout->title = Lang::get('manage_users');

        return $this->view->render();
    }

    /**
     * Allows the user to edit their profile.
     * @return string
     */
    public function profile()
    {
        $user = $_SESSION['phpci_user'];

        if ($this->request->getMethod() == 'POST') {
            $name = $this->getParam('name', null);
            $email = $this->getParam('email', null);
            $password = $this->getParam('password', null);

            $currentLang = Lang::getLanguage();
            $chosenLang = $this->getParam('language', $currentLang);

            if ($chosenLang !== $currentLang) {
                setcookie('phpcilang', $chosenLang, time() + (10 * 365 * 24 * 60 * 60), '/');
                Lang::setLanguage($chosenLang);
            }

            $_SESSION['phpci_user'] = $this->userService->updateUser($user, $name, $email, $password);
            $user = $_SESSION['phpci_user'];

            $this->view->updated = 1;
        }

        $this->layout->title = $user->getName();
        $this->layout->subtitle = Lang::get('edit_profile');

        $values = $user->getDataArray();

        if (array_key_exists('phpcilang', $_COOKIE)) {
            $values['language'] = $_COOKIE['phpcilang'];
        }

        $form = new Form();
        $form->setAction(PHPCI_URL.'user/profile');
        $form->setMethod('POST');

        $name = new Form\Element\Text('name');
        $name->setClass('form-control');
        $name->setContainerClass('form-group');
        $name->setLabel(Lang::get('name'));
        $name->setRequired(true);
        $form->addField($name);

        $email = new Form\Element\Email('email');
        $email->setClass('form-control');
        $email->setContainerClass('form-group');
        $email->setLabel(Lang::get('email_address'));
        $email->setRequired(true);
        $form->addField($email);

        $password = new Form\Element\Password('password');
        $password->setClass('form-control');
        $password->setContainerClass('form-group');
        $password->setLabel(Lang::get('password_change'));
        $password->setRequired(false);
        $form->addField($password);

        $lang = new Form\Element\Select('language');
        $lang->setClass('form-control');
        $lang->setContainerClass('form-group');
        $lang->setLabel(Lang::get('language'));
        $lang->setRequired(true);
        $lang->setOptions(Lang::getLanguageOptions());
        $form->addField($lang);

        $submit = new Form\Element\Submit();
        $submit->setClass('btn btn-success');
        $submit->setValue(Lang::get('save'));
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
        $this->requireAdmin();

        $this->layout->title = Lang::get('add_user');

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

        $response = new b8\Http\Response\RedirectResponse();
        $response->setHeader('Location', PHPCI_URL . 'user');
        return $response;
    }

    /**
    * Edit a user - handles both form and processing.
    */
    public function edit($userId)
    {
        $this->requireAdmin();

        $method = $this->request->getMethod();
        $user = $this->userStore->getById($userId);

        if (empty($user)) {
            throw new NotFoundException(Lang::get('user_n_not_found', $userId));
        }

        $this->layout->title = $user->getName();
        $this->layout->subtitle = Lang::get('edit_user');

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

        $response = new b8\Http\Response\RedirectResponse();
        $response->setHeader('Location', PHPCI_URL . 'user');
        return $response;
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
        $field->setLabel(Lang::get('email_address'));
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Text('name');
        $field->setRequired(true);
        $field->setLabel(Lang::get('name'));
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Password('password');

        if ($type == 'add') {
            $field->setRequired(true);
            $field->setLabel(Lang::get('password'));
        } else {
            $field->setRequired(false);
            $field->setLabel(Lang::get('password_change'));
        }

        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Checkbox('is_admin');
        $field->setRequired(false);
        $field->setCheckedValue(1);
        $field->setLabel(Lang::get('is_user_admin'));
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue(Lang::get('save_user'));
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
        $this->requireAdmin();

        $user   = $this->userStore->getById($userId);

        if (empty($user)) {
            throw new NotFoundException(Lang::get('user_n_not_found', $userId));
        }

        $this->userService->deleteUser($user);

        $response = new b8\Http\Response\RedirectResponse();
        $response->setHeader('Location', PHPCI_URL . 'user');
        return $response;
    }
}
