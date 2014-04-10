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
use b8\Form;
use b8\HttpClient;
use PHPCI\Controller;
use PHPCI\Model\Build;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

/**
 * Settings Controller
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Web
 */
class SettingsController extends Controller
{
    protected $settings;

    public function init()
    {
        parent::init();

        $parser = new Parser();
        $yaml = file_get_contents(APPLICATION_PATH . 'PHPCI/config.yml');
        $this->settings = $parser->parse($yaml);
    }

    public function index()
    {
        $this->view->settings = $this->settings;

        $emailSettings = array();

        if (isset($this->settings['phpci']['email_settings'])) {
            $emailSettings = $this->settings['phpci']['email_settings'];
        }

        $this->view->github = $this->getGithubForm();
        $this->view->emailSettings = $this->getEmailForm($emailSettings);

        if (!empty($this->settings['phpci']['github']['token'])) {
            $this->view->githubUser = $this->getGithubUser($this->settings['phpci']['github']['token']);
        }

        return $this->view->render();
    }

    public function github()
    {
        $this->settings['phpci']['github']['id'] = $this->getParam('githubid', '');
        $this->settings['phpci']['github']['secret'] = $this->getParam('githubsecret', '');
        $error = $this->storeSettings();

        if($error) {
            header('Location: ' . PHPCI_URL . 'settings?saved=2');
        } else {
            header('Location: ' . PHPCI_URL . 'settings?saved=1');
        }

        die;
    }

    public function email()
    {
        $this->settings['phpci']['email_settings'] = $this->getParams();
        $error = $this->storeSettings();

        if ($error) {
            header('Location: ' . PHPCI_URL . 'settings?saved=2');
        } else {
            header('Location: ' . PHPCI_URL . 'settings?saved=1');
        }

        die;
    }

    /**
     * Github redirects users back to this URL when t
     */
    public function githubCallback()
    {
        $code = $this->getParam('code', null);
        $github = $this->settings['phpci']['github'];

        if (!is_null($code)) {
            $http = new HttpClient();
            $url  = 'https://github.com/login/oauth/access_token';
            $params = array('client_id' => $github['id'], 'client_secret' => $github['secret'], 'code' => $code);
            $resp = $http->post($url, $params);

            if ($resp['success']) {
                parse_str($resp['body'], $resp);

                $this->settings['phpci']['github']['token'] = $resp['access_token'];
                $this->storeSettings();

                header('Location: ' . PHPCI_URL . 'settings?linked=1');
                die;
            }
        }


        header('Location: ' . PHPCI_URL . 'settings?linked=2');
        die;
    }

    protected function storeSettings()
    {
        $dumper = new Dumper();
        $yaml = $dumper->dump($this->settings);
        file_put_contents(APPLICATION_PATH . 'PHPCI/config.yml', $yaml);
        if (error_get_last()) {
            $error_get_last = error_get_last();
            return $error_get_last['message'];
        }
    }

    protected function getGithubForm()
    {
        $form = new Form();
        $form->setMethod('POST');
        $form->setAction(PHPCI_URL . 'settings/github');
        $form->addField(new Form\Element\Csrf('csrf'));

        $field = new Form\Element\Text('githubid');
        $field->setRequired(true);
        $field->setPattern('[a-zA-Z0-9]+');
        $field->setLabel('Application ID');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        if (isset($this->settings['phpci']['github']['id'])) {
            $field->setValue($this->settings['phpci']['github']['id']);
        }

        $field = new Form\Element\Text('githubsecret');
        $field->setRequired(true);
        $field->setPattern('[a-zA-Z0-9]+');
        $field->setLabel('Application Secret');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        if (isset($this->settings['phpci']['github']['secret'])) {
            $field->setValue($this->settings['phpci']['github']['secret']);
        }

        $field = new Form\Element\Submit();
        $field->setValue('Save &raquo;');
        $field->setClass('btn btn-success pull-right');
        $form->addField($field);

        return $form;
    }

    protected function getEmailForm($values = array())
    {
        $form = new Form();
        $form->setMethod('POST');
        $form->setAction(PHPCI_URL . 'settings/email');
        $form->addField(new Form\Element\Csrf('csrf'));

        $field = new Form\Element\Text('smtp_address');
        $field->setRequired(false);
        $field->setLabel('SMTP Server');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $field->setValue('localhost');
        $form->addField($field);

        $field = new Form\Element\Text('smtp_port');
        $field->setRequired(false);
        $field->setPattern('[0-9]+');
        $field->setLabel('SMTP Port');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $field->setValue(25);
        $form->addField($field);

        $field = new Form\Element\Text('smtp_username');
        $field->setRequired(false);
        $field->setLabel('SMTP Username');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Text('smtp_password');
        $field->setRequired(false);
        $field->setLabel('SMTP Password');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Email('from_address');
        $field->setRequired(false);
        $field->setLabel('From Email Address');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Email('default_mailto_address');
        $field->setRequired(false);
        $field->setLabel('Default Notification Address');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Checkbox('smtp_encryption');
        $field->setCheckedValue(1);
        $field->setRequired(false);
        $field->setLabel('Use SMTP encryption?');
        $field->setContainerClass('form-group');
        $field->setValue(1);
        $form->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue('Save &raquo;');
        $field->setClass('btn btn-success pull-right');
        $form->addField($field);

        $form->setValues($values);

        return $form;
    }

    protected function getGithubUser($token)
    {
        $http = new HttpClient('https://api.github.com');
        $user = $http->get('/user', array('access_token' => $token));

        return $user['body'];
    }
}
