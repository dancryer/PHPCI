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
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

/**
 * Settings Controller
 *
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Web
 */
class SettingsController extends Controller
{

    /**
     * @var array
     */
    protected $settings;

    /**
     * Initialise the controller, set up stores and services.
     */
    public function init()
    {
        parent::init();

        $parser         = new Parser();
        $yaml           = file_get_contents(APPLICATION_PATH . 'PHPCI/config.yml');
        $this->settings = $parser->parse($yaml);
    }

    /**
     * Display settings forms.
     * @return string
     */
    public function index()
    {
        $this->requireAdmin();

        $this->layout->title = Lang::get('settings');

        $this->view->settings = $this->settings;

        $basicSettings = array();
        if (isset($this->settings['phpci']['basic'])) {
            $basicSettings = $this->settings['phpci']['basic'];
        }

        $buildSettings = array();
        if (isset($this->settings['phpci']['build'])) {
            $buildSettings = $this->settings['phpci']['build'];
        }

        $emailSettings = array();
        if (isset($this->settings['phpci']['email_settings'])) {
            $emailSettings = $this->settings['phpci']['email_settings'];
        }

        $authSettings = array();
        if (isset($this->settings['phpci']['authentication_settings'])) {
            $authSettings = $this->settings['phpci']['authentication_settings'];
        }

        $this->view->basicSettings = $this->getBasicForm($basicSettings);
        $this->view->buildSettings = $this->getBuildForm($buildSettings);
        $this->view->github = $this->getGithubForm();
        $this->view->emailSettings = $this->getEmailForm($emailSettings);
        $this->view->authenticationSettings = $this->getAuthenticationForm($authSettings);
        $this->view->isWriteable = $this->canWriteConfig();

        if (!empty($this->settings['phpci']['github']['token'])) {
            $this->view->githubUser = $this->getGithubUser($this->settings['phpci']['github']['token']);
        }

        return $this->view->render();
    }

    /**
     * Save Github settings.
     */
    public function github()
    {
        $this->requireAdmin();

        $this->settings['phpci']['github']['id']     = $this->getParam('githubid', '');
        $this->settings['phpci']['github']['secret'] = $this->getParam('githubsecret', '');
        $error                                       = $this->storeSettings();

        $response = new b8\Http\Response\RedirectResponse();

        if ($error) {
            $response->setHeader('Location', PHPCI_URL . 'settings?saved=2');
        } else {
            $response->setHeader('Location', PHPCI_URL . 'settings?saved=1');
        }

        return $response;
    }

    /**
     * Save email settings.
     */
    public function email()
    {
        $this->requireAdmin();

        $this->settings['phpci']['email_settings']                    = $this->getParams();
        $this->settings['phpci']['email_settings']['smtp_encryption'] = $this->getParam('smtp_encryption', 0);

        $error = $this->storeSettings();

        $response = new b8\Http\Response\RedirectResponse();

        if ($error) {
            $response->setHeader('Location', PHPCI_URL . 'settings?saved=2');
        } else {
            $response->setHeader('Location', PHPCI_URL . 'settings?saved=1');
        }

        return $response;
    }

    /**
     * Save build settings.
     */
    public function build()
    {
        $this->requireAdmin();

        $this->settings['phpci']['build'] = $this->getParams();

        $error = $this->storeSettings();

        $response = new b8\Http\Response\RedirectResponse();

        if ($error) {
            $response->setHeader('Location', PHPCI_URL . 'settings?saved=2');
        } else {
            $response->setHeader('Location', PHPCI_URL . 'settings?saved=1');
        }

        return $response;
    }

    /**
     * Save basic settings.
     */
    public function basic()
    {
        $this->requireAdmin();

        $this->settings['phpci']['basic'] = $this->getParams();
        $error = $this->storeSettings();

        $response = new b8\Http\Response\RedirectResponse();

        if ($error) {
            $response->setHeader('Location', PHPCI_URL . 'settings?saved=2');
        } else {
            $response->setHeader('Location', PHPCI_URL . 'settings?saved=1');
        }

        return $response;
    }

    /**
     * Handle authentication settings
     */
    public function authentication()
    {
        $this->requireAdmin();

        $this->settings['phpci']['authentication_settings']['state']   = $this->getParam('disable_authentication', 0);
        $this->settings['phpci']['authentication_settings']['user_id'] = $_SESSION['phpci_user_id'];

        $error = $this->storeSettings();

        $response = new b8\Http\Response\RedirectResponse();

        if ($error) {
            $response->setHeader('Location', PHPCI_URL . 'settings?saved=2');
        } else {
            $response->setHeader('Location', PHPCI_URL . 'settings?saved=1');
        }

        return $response;
    }

    /**
     * Github redirects users back to this URL when t
     */
    public function githubCallback()
    {
        $code   = $this->getParam('code', null);
        $github = $this->settings['phpci']['github'];

        if (!is_null($code)) {
            $http   = new HttpClient();
            $url    = 'https://github.com/login/oauth/access_token';
            $params = array('client_id' => $github['id'], 'client_secret' => $github['secret'], 'code' => $code);
            $resp   = $http->post($url, $params);

            if ($resp['success']) {
                parse_str($resp['body'], $resp);

                $this->settings['phpci']['github']['token'] = $resp['access_token'];
                $this->storeSettings();

                $response = new b8\Http\Response\RedirectResponse();
                $response->setHeader('Location', PHPCI_URL . 'settings?linked=1');
                return $response;
            }
        }

        $response = new b8\Http\Response\RedirectResponse();
        $response->setHeader('Location', PHPCI_URL . 'settings?linked=2');
        return $response;
    }

    /**
     * Convert config to yaml and store to file.
     *
     * @return mixed
     */
    protected function storeSettings()
    {
        $dumper = new Dumper();
        $yaml   = $dumper->dump($this->settings, 4);
        file_put_contents(APPLICATION_PATH . 'PHPCI/config.yml', $yaml);

        if (error_get_last()) {
            $error_get_last = error_get_last();
            return $error_get_last['message'];
        }
    }

    /**
     * Get the Github settings form.
     * @return Form
     */
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

    /**
     * Get the email settings form.
     * @param array $values
     * @return Form
     */
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

    /**
     * Call Github API for our Github user object.
     * @param $token
     * @return mixed
     */
    protected function getGithubUser($token)
    {
        $http = new HttpClient('https://api.github.com');
        $user = $http->get('/user', array('access_token' => $token));

        return $user['body'];
    }

    /**
     * Check if we can write the PHPCI config file.
     * @return bool
     */
    protected function canWriteConfig()
    {
        return is_writeable(APPLICATION_PATH . 'PHPCI/config.yml');
    }

    /**
     * Get the Build settings form.
     * @param array $values
     * @return Form
     */
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

    /**
     * Get the Basic settings form.
     * @param array $values
     * @return Form
     */
    protected function getBasicForm($values = array())
    {
        $form = new Form();
        $form->setMethod('POST');
        $form->setAction(PHPCI_URL . 'settings/basic');

        $field = new Form\Element\Select('language');
        $field->setRequired(true);
        $field->setLabel(Lang::get('language'));
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $field->setOptions(Lang::getLanguageOptions());
        $field->setValue('en');
        $form->addField($field);


        $field = new Form\Element\Submit();
        $field->setValue(Lang::get('save'));
        $field->setClass('btn btn-success pull-right');
        $form->addField($field);

        $form->setValues($values);

        return $form;
    }

    /**
     * Form for disabling user authentication while using a default user
     *
     * @param array $values
     * @return Form
     */
    protected function getAuthenticationForm($values = array())
    {
        $form = new Form();
        $form->setMethod('POST');
        $form->setAction(PHPCI_URL . 'settings/authentication');
        $form->addField(new Form\Element\Csrf('csrf'));

        $field = new Form\Element\Checkbox('disable_authentication');
        $field->setCheckedValue(1);
        $field->setRequired(false);
        $field->setLabel('Disable Authentication?');
        $field->setContainerClass('form-group');
        $field->setValue(0);

        if (isset($values['state'])) {
            $field->setValue((int)$values['state']);
        }

        $form->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue('Save &raquo;');
        $field->setClass('btn btn-success pull-right');
        $form->addField($field);

        $form->setValues($values);

        return $form;
    }
}
