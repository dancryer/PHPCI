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

/**
* Project Controller - Allows users to create, edit and view projects.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class ProjectController extends \PHPCI\Controller
{
    /**
     * @var \PHPCI\Store\BuildStore
     */
    protected $buildStore;

    /**
     * @var \PHPCI\Store\ProjectStore
     */
    protected $projectStore;

    public function init()
    {
        $this->buildStore      = Store\Factory::getStore('Build');
        $this->projectStore    = Store\Factory::getStore('Project');
    }

    /**
    * View a specific project.
    */
    public function view($projectId)
    {
        $project        = $this->projectStore->getById($projectId);
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
        /* @var \PHPCI\Model\Project $project */
        $project = $this->projectStore->getById($projectId);

        $build = new Build();
        $build->setProjectId($projectId);
        $build->setCommitId('Manual');
        $build->setStatus(0);
        $build->setBranch($project->getType() === 'hg' ? 'default' : 'master');
        $build->setCreated(new \DateTime());

        $build = $this->buildStore->save($build);

        header('Location: '.PHPCI_URL.'build/view/' . $build->getId());
        exit;
    }

    /**
    * Delete a project.
    */
    public function delete($projectId)
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new \Exception('You do not have permission to do that.');
        }

        $project    = $this->projectStore->getById($projectId);
        $this->projectStore->delete($project);

        header('Location: '.PHPCI_URL);
        exit;
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
        $builds         = $this->buildStore->getWhere($criteria, 10, $start, array(), $order);
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

        if ($values['type'] == "gitlab") {
            preg_match('`^(.*)@(.*):(.*)/(.*)\.git`', $values['reference'], $matches);
            $info = array();
            $info["user"] = $matches[1];
            $info["domain"] = $matches[2];
            $values['access_information'] = serialize($info);
            $values['reference'] = $matches[3]."/".$matches[4];
        }

        $values['git_key']  = $values['key'];

        $project = new Project();
        $project->setValues($values);

        $project = $this->projectStore->save($project);

        header('Location: '.PHPCI_URL.'project/view/' . $project->getId());
        die;
    }

    /**
    * Handles log in with Github
    */
    protected function handleGithubResponse()
    {
        $github = \b8\Config::getInstance()->get('phpci.github');
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
        $project    = $this->projectStore->getById($projectId);

        if ($method == 'POST') {
            $values = $this->getParams();
        } else {
            $values         = $project->getDataArray();
            $values['key']  = $values['git_key'];
            if ($values['type'] == "gitlab") {
                $accessInfo = $project->getAccessInformation();
                $reference = $accessInfo["user"].'@'.$accessInfo["domain"].':' . $project->getReference().".git";
                $values['reference'] = $reference;
            }
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

        if ($values['type'] == "gitlab") {
            preg_match('`^(.*)@(.*):(.*)/(.*)\.git`', $values['reference'], $matches);
            $info = array();
            $info["user"] = $matches[1];
            $info["domain"] = $matches[2];
            $values['access_information'] = serialize($info);
            $values['reference'] = $matches[3] . "/" . $matches[4];
        }

        $project->setValues($values);
        $project = $this->projectStore->save($project);

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
            'gitlab' => 'Gitlab',
            'remote' => 'Remote URL',
            'local' => 'Local Path',
            'hg'    => 'Mercurial',
            );

        $field = new Form\Element\Select('type');
        $field->setRequired(true);
        $field->setPattern('^(github|bitbucket|gitlab|remote|local|hg)');
        $field->setOptions($options);
        $field->setLabel('Where is your project hosted?');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        if (isset($_SESSION['github_token'])) {
            $field = new Form\Element\Select('github');
            $field->setLabel('Choose a Github repository:');
            $field->setClass('form-control');
            $field->setContainerClass('form-group');
            $field->setOptions($this->getGithubRepositories());
            $form->addField($field);
        }

        $field = new Form\Element\Text('reference');
        $field->setRequired(true);
        $field->setValidator($this->getReferenceValidator($values));
        $field->setLabel('Repository Name / URL (Remote) or Path (Local)');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\Text('title');
        $field->setRequired(true);
        $field->setLabel('Project Title');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $form->addField($field);

        $field = new Form\Element\TextArea('key');
        $field->setRequired(false);
        $field->setLabel('Private key to use to access repository (leave blank for local and/or anonymous remotes)');
        $field->setClass('form-control');
        $field->setContainerClass('form-group');
        $field->setRows(6);
        $form->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue('Save Project');
        $field->setContainerClass('form-group');
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

    protected function getReferenceValidator($values)
    {
        return function ($val) use ($values) {
            $type = $values['type'];

            $validators = array(
                'hg' => array(
                    'regex' => '/^(https?):\/\//',
                    'message' => 'Mercurial repository URL must be start with http:// or https://'
                ),
                'remote' => array(
                    'regex' => '/^(git|https?):\/\//',
                    'message' => 'Repository URL must be start with git://, http:// or https://'
                ),
                'gitlab' => array(
                    'regex' => '`^(.*)@(.*):(.*)/(.*)\.git`',
                    'message' => 'GitLab Repository name must be in the format "user@domain.tld:owner/repo.git"'
                ),
                'github' => array(
                    'regex' => '/^[a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-\.]+$/',
                    'message' => 'Repository name must be in the format "owner/repo"'
                ),
                'bitbucket' => array(
                    'regex' => '/^[a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-\.]+$/',
                    'message' => 'Repository name must be in the format "owner/repo"'
                ),
            );

            if (in_array($type, $validators) && !preg_match($validators[$type]['regex'], $val)) {
                throw new \Exception($validators[$type]['message']);
            } elseif ($type == 'local' && !is_dir($val)) {
                throw new \Exception('The path you specified does not exist.');
            }

            return true;
        };
    }
}
