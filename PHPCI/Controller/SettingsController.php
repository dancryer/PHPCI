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
use b8\Form;
use b8\HttpClient;
use PHPCI\Controller;
use PHPCI\Helper\Lang;
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
        $this->requireAdmin();

        $this->layout->title = Lang::get('settings');
        $this->view->settings = $this->settings;

        $emailSettings = array();
        if (isset($this->settings['phpci']['email_settings'])) {
            $emailSettings = $this->settings['phpci']['email_settings'];
        }

        $buildSettings = array();
        if (isset($this->settings['phpci']['build'])) {
            $buildSettings = $this->settings['phpci']['build'];
        }

        $this->view->github = $this->getGithubForm();
        $this->view->emailSettings = $this->getEmailForm($emailSettings);
        $this->view->buildSettings = $this->getBuildForm($buildSettings);
        $this->view->isWriteable = $this->canWriteConfig();

        if (!empty($this->settings['phpci']['github']['token'])) {
            $this->view->githubUser = $this->getGithubUser($this->settings['phpci']['github']['token']);
        }

        return $this->view->render();
    }

    public function github()
    {
        $this->requireAdmin();

        $this->settings['phpci']['github']['id'] = $this->getParam('githubid', '');
        $this->settings['phpci']['github']['secret'] = $this->getParam('githubsecret', '');
        $error = $this->storeSettings();

        if ($error) {
            header('Location: ' . PHPCI_URL . 'settings?saved=2');
        } else {
            header('Location: ' . PHPCI_URL . 'settings?saved=1');
        }

        die;
    }

    public function email()
    {
        $this->requireAdmin();

        $this->settings['phpci']['email_settings'] = $this->getParams();
        $this->settings['phpci']['email_settings']['smtp_encryption'] = $this->getParam('smtp_encryption', 0);

        $error = $this->storeSettings();

        if ($error) {
            header('Location: ' . PHPCI_URL . 'settings?saved=2');
        } else {
            header('Location: ' . PHPCI_URL . 'settings?saved=1');
        }

        die;
    }

    public function build()
    {
        $this->requireAdmin();

        $this->settings['phpci']['build'] = $this->getParams();

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

    /**
     * Convert config to yaml and store to file.
     * @return mixed
     */
    protected function storeSettings()
    {
        $dumper = new Dumper();
        $yaml = $dumper->dump($this->settings, 4);
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
        $field->setLabel(Lang::get('application_id'));
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        if (isset($this->settings['phpci']['github']['id'])) {
            $field->setValue($this->settings['phpci']['github']['id']);
        }

        $field = new Form\Element\Text('githubsecret');
        $field->setRequired(true);
        $field->setPattern('[a-zA-Z0-9]+');
        $field->setLabel(Lang::get('application_secret'));
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        if (isset($this->settings['phpci']['github']['secret'])) {
            $field->setValue($this->settings['phpci']['github']['secret']);
        }

        $field = new Form\Element\Submit();
        $field->setValue(Lang::get('save'));
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
        $field->setLabel(Lang::get('smtp_server'));
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $field->setValue('localhost');
        $form->addField($field);

        $field = new Form\Element\Text('smtp_port');
        $field->setRequired(false);
        $field->setPattern('[0-9]+');
        $field->setLabel(Lang::get('smtp_port'));
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $field->setValue(25);
        $form->addField($field);

        $field = new Form\Element\Text('smtp_username');
        $field->setRequired(false);
        $field->setLabel(Lang::get('smtp_username'));
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Text('smtp_password');
        $field->setRequired(false);
        $field->setLabel(Lang::get('smtp_password'));
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Email('from_address');
        $field->setRequired(false);
        $field->setLabel(Lang::get('from_email_address'));
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Email('default_mailto_address');
        $field->setRequired(false);
        $field->setLabel(Lang::get('default_notification_address'));
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Select('smtp_encryption');
        $field->setOptions(['' => Lang::get('none'), 'tls' => Lang::get('tls'), 'ssl' => Lang::get('ssl')]);
        $field->setRequired(false);
        $field->setLabel(Lang::get('use_smtp_encryption'));
        $field->setContainerClass('form-group');
        $field->setValue(1);
        $form->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue(Lang::get('save'));
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

    protected function canWriteConfig()
    {
        return is_writeable(APPLICATION_PATH . 'PHPCI/config.yml');
    }

    protected function getBuildForm($values = array())
    {
        $form = new Form();
        $form->setMethod('POST');
        $form->setAction(PHPCI_URL . 'settings/build');

        $field = new Form\Element\Select('failed_after');
        $field->setRequired(false);
        $field->setLabel(Lang::get('failed_after'));
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $field->setOptions([
            300 => Lang::get('5_mins'),
            900 => Lang::get('15_mins'),
            1800 => Lang::get('30_mins'),
            3600 => Lang::get('1_hour'),
            10800 => Lang::get('3_hours'),
        ]);
        $field->setValue(1800);
        $form->addField($field);


        $field = new Form\Element\Submit();
        $field->setValue(Lang::get('save'));
        $field->setClass('btn btn-success pull-right');
        $form->addField($field);

        $form->setValues($values);

        return $form;
    }
}
