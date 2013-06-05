<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Controller;

use PHPCI\Model\Build;
use PHPCI\Model\Project;
use b8;
use b8\Controller;
use b8\Store;
use b8\Form;
use b8\Registry;

/**
* Project Controller - Allows users to create, edit and view projects.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class ProjectController extends \PHPCI\Controller
{
    public function init()
    {
        $this->_buildStore      = Store\Factory::getStore('Build');
        $this->_projectStore    = Store\Factory::getStore('Project');
    }

    /**
    * View a specific project.
    */
    public function view($projectId)
    {
        $project        = $this->_projectStore->getById($projectId);
        $page           = $this->getParam('p', 1);
        $builds         = $this->getLatestBuildsHtml($projectId, (($page - 1) * 10));

        $this->view->builds   = $builds[0];
        $this->view->total    = $builds[1];
        $this->view->project  = $project;
        $this->view->page     = $page;

        return $this->view->render();
    }

    /**
    * Create a new pending build for a project.
    */
    public function build($projectId)
    {
        $build = new Build();
        $build->setProjectId($projectId);
        $build->setCommitId('Manual');
        $build->setStatus(0);
        $build->setBranch('master');
        $build->setCreated(new \DateTime());

        $build = $this->_buildStore->save($build);

        header('Location: '.PHPCI_URL.'build/view/' . $build->getId());
    }

    /**
    * Delete a project.
    */
    public function delete($projectId)
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new \Exception('You do not have permission to do that.');
        }

        $project    = $this->_projectStore->getById($projectId);
        $this->_projectStore->delete($project);

        header('Location: '.PHPCI_URL);
    }

    /**
    * AJAX get latest builds.
    */
    public function builds($projectId)
    {
        $builds = $this->getLatestBuildsHtml($projectId);
        die($builds[0]);
    }

    /**
    * Render latest builds for project as HTML table.
    */
    protected function getLatestBuildsHtml($projectId, $start = 0)
    {
        $criteria       = array('project_id' => $projectId);
        $order          = array('id' => 'DESC');
        $builds         = $this->_buildStore->getWhere($criteria, 10, $start, array(), $order);
        $view           = new b8\View('BuildsTable');
        $view->builds   = $builds['items'];

        return array($view->render(), $builds['count']);
    }

    /**
    * Add a new project. Handles both the form, and processing.
    */
    public function add()
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new \Exception('You do not have permission to do that.');
        }

        $method = $this->request->getMethod();
        $this->handleGithubResponse();

        if ($method == 'POST') {
            $values = $this->getParams();
            $pub = null;
        } else {
            $tempPath = sys_get_temp_dir() . '/';

            // FastCGI fix for Windows machines, where temp path is not available to
            // PHP, and defaults to the unwritable system directory.  If the temp
            // path is pointing to the system directory, shift to the 'TEMP'
            // sub-folder, which should also exist, but actually be writable.
            if ($tempPath == getenv("SystemRoot") . '/') {
                $tempPath = getenv("SystemRoot") . '/TEMP/';
            }

            $keyFile = $tempPath . md5(microtime(true));

            if (!is_dir($tempPath)) {
                mkdir($tempPath);
            }

            shell_exec('ssh-keygen -q -t rsa -b 2048 -f '.$keyFile.' -N "" -C "deploy@phpci"');

            $pub = file_get_contents($keyFile . '.pub');
            $prv = file_get_contents($keyFile);

            $values = array('key' => $prv, 'pubkey' => $pub, 'token' => $_SESSION['github_token']);
        }

        $form   = $this->projectForm($values);

        if ($method != 'POST' || ($method == 'POST' && !$form->validate())) {
            $view           = new b8\View('ProjectForm');
            $view->type     = 'add';
            $view->project  = null;
            $view->form     = $form;
            $view->key      = $pub;
            $view->token    = $_SESSION['github_token'];

            return $view->render();
        }

        $values             = $form->getValues();
        $values['git_key']  = $values['key'];

        $project = new Project();
        $project->setValues($values);

        $project = $this->_projectStore->save($project);

        header('Location: '.PHPCI_URL.'project/view/' . $project->getId());
        die;
    }

    /**
    * Handles log in with Github
    */
    protected function handleGithubResponse()
    {
        $github = \b8\Registry::getInstance()->get('github_app');
        $code   = $this->getParam('code', null);

        if (!is_null($code)) {
            $http = new \b8\HttpClient();
            $url  = 'https://github.com/login/oauth/access_token';
            $params = array('client_id' => $github['id'], 'client_secret' => $github['secret'], 'code' => $code);
            $resp = $http->post($url, $params);
            
            if ($resp['success']) {
                parse_str($resp['body'], $resp);
                $_SESSION['github_token'] = $resp['access_token'];
                header('Location: '.PHPCI_URL.'project/add');
                die;
            }
        }

        if (!isset($_SESSION['github_token'])) {
            $_SESSION['github_token'] = null;
        }
    }

    /**
    * Edit a project. Handles both the form and processing. 
    */
    public function edit($projectId)
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new \Exception('You do not have permission to do that.');
        }
        
        $method     = $this->request->getMethod();
        $project    = $this->_projectStore->getById($projectId);

        if ($method == 'POST') {
            $values = $this->getParams();
        } else {
            $values         = $project->getDataArray();
            $values['key']  = $values['git_key'];
        }

        $form   = $this->projectForm($values, 'edit/' . $projectId);

        if ($method != 'POST' || ($method == 'POST' && !$form->validate())) {
            $view           = new b8\View('ProjectForm');
            $view->type     = 'edit';
            $view->project  = $project;
            $view->form     = $form;
            $view->key      = null;

            return $view->render();
        }

        $values             = $form->getValues();
        $values['git_key']  = $values['key'];

        $project->setValues($values);
        $project = $this->_projectStore->save($project);

        header('Location: '.PHPCI_URL.'project/view/' . $project->getId());
        die;
    }

    /**
    * Create add / edit project form. 
    */
    protected function projectForm($values, $type = 'add')
    {
        $form = new Form();
        $form->setMethod('POST');
        $form->setAction(PHPCI_URL.'project/' . $type);
        $form->addField(new Form\Element\Csrf('csrf'));
        $form->addField(new Form\Element\Hidden('token'));
        $form->addField(new Form\Element\Hidden('pubkey'));

        $options = array(
            'choose' => 'Select repository type...',
            'github' => 'Github',
            'bitbucket' => 'Bitbucket',
            'local' => 'Local Path'
            );

        $field = new Form\Element\Select('type');
        $field->setRequired(true);
        $field->setPattern('^(github|bitbucket|local)');
        $field->setOptions($options);
        $field->setLabel('Where is your project hosted?');
        $field->setClass('span4');
        $form->addField($field);

        if (isset($_SESSION['github_token'])) {
            $field = new Form\Element\Select('github');
            $field->setLabel('Choose a Github repository:');
            $field->setClass('span4');
            $field->setOptions($this->getGithubRepositories());
            $form->addField($field);
        }

        $referenceValidator = function ($val) use ($values) {
            $type = $values['type'];

            switch($type) {
                case 'local':
                    if (!is_dir($val)) {
                        throw new \Exception('The path you specified does not exist.');
                    }
                    break;
                case 'github':
                case 'bitbucket':
                    if (!preg_match('/^[a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-]+$/', $val)) {
                        throw new \Exception('Repository name must be in the format "owner/repo".');
                    }
                    break;
            }

            return true;
        };

        $field = new Form\Element\Text('reference');
        $field->setRequired(true);
        $field->setValidator($referenceValidator);
        $field->setLabel('Repository Name / URL (Remote) or Path (Local)');
        $field->setClass('span4');
        $form->addField($field);

        $field = new Form\Element\Text('title');
        $field->setRequired(true);
        $field->setLabel('Project Title');
        $field->setClass('span4');
        $form->addField($field);
        
        $field = new Form\Element\TextArea('key');
        $field->setRequired(false);
        $field->setLabel('Private key to use to access repository (leave blank for local and/or anonymous remotes)');
        $field->setClass('span7');
        $field->setRows(6);
        $form->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue('Save Project');
        $field->setClass('btn-success');
        $form->addField($field);

        $form->setValues($values);
        return $form;
    }

    /**
    * Get an array of repositories from Github's API.
    */
    protected function getGithubRepositories()
    {
        $http = new \b8\HttpClient();
        $url = 'https://api.github.com/user/repos';
        $res = $http->get($url, array('type' => 'all', 'access_token' => $_SESSION['github_token']));

        $rtn = array();
        $rtn['choose'] = 'Select a repository...';
        if ($res['success']) {
            foreach ($res['body'] as $repo) {
                $rtn[$repo['full_name']] = $repo['full_name'];
            }
        }

        return $rtn;
    }
}
