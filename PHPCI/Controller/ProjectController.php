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
use b8\Controller;
use b8\Form;
use b8\Exception\HttpException\ForbiddenException;
use b8\Exception\HttpException\NotFoundException;
use b8\Store;
use PHPCI\BuildFactory;
use PHPCI\Helper\Github;
use PHPCI\Helper\SshKey;
use PHPCI\Model\Build;
use PHPCI\Model\Project;

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
        $this->buildStore = Store\Factory::getStore('Build');
        $this->projectStore = Store\Factory::getStore('Project');
    }

    /**
    * View a specific project.
    */
    public function view($projectId)
    {
        $project = $this->projectStore->getById($projectId);

        if (empty($project)) {
            throw new NotFoundException('Project with id: ' . $projectId . ' not found');
        }

        $per_page = 10;
        $page     = $this->getParam('p', 1);
        $builds   = $this->getLatestBuildsHtml($projectId, (($page - 1) * $per_page));
        $pages    = $builds[1] == 0 ? 1 : ceil($builds[1] / $per_page);

        if ($page > $pages) {
            throw new NotFoundException('Page with number: ' . $page . ' not found');
        }

        $this->view->builds  = $builds[0];
        $this->view->total   = $builds[1];
        $this->view->project = $project;
        $this->view->page    = $page;
        $this->view->pages   = $pages;

        $this->config->set('page_title', $project->getTitle());

        return $this->view->render();
    }

    /**
    * Create a new pending build for a project.
    */
    public function build($projectId)
    {
        /* @var \PHPCI\Model\Project $project */
        $project = $this->projectStore->getById($projectId);

        if (empty($project)) {
            throw new NotFoundException('Project with id: ' . $projectId . ' not found');
        }

        $build = new Build();
        $build->setProjectId($projectId);
        $build->setCommitId('Manual');
        $build->setStatus(Build::STATUS_NEW);
        $build->setBranch($project->getBranch());
        $build->setCreated(new \DateTime());
        $build->setCommitterEmail($_SESSION['user']->getEmail());

        $build = $this->buildStore->save($build);

        header('Location: '.PHPCI_URL.'build/view/' . $build->getId());
        exit;
    }

    /**
    * Delete all builds of a project
    */
    public function clean($projectId)
    {
        if (empty($_SESSION['user']) || !$_SESSION['user']->getIsAdmin()) {
            throw new \Exception('You do not have permission to do that.');
        }

        /* @var \PHPCI\Model\Project $project */
        $project = $this->projectStore->getById($projectId);

        if (empty($project)) {
            throw new NotFoundException('Project with id: ' . $projectId . ' not found');
        }
        
        if (!$project->cleanBuilds()) {
            throw new \Exception('Unable to clean up of builds of project.');
        }

        header('Location: '.PHPCI_URL.'project/view/' . $projectId);
        exit;
    }

    /**
    * Delete a project.
    */
    public function delete($projectId)
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new ForbiddenException('You do not have permission to do that.');
        }

        $project = $this->projectStore->getById($projectId);
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
        $criteria = array('project_id' => $projectId);
        $order = array('id' => 'DESC');
        $builds = $this->buildStore->getWhere($criteria, 10, $start, array(), $order);
        $view = new b8\View('BuildsTable');

        foreach ($builds['items'] as &$build) {
            $build = BuildFactory::getBuild($build);
        }

        $view->builds   = $builds['items'];

        return array($view->render(), $builds['count']);
    }

    /**
    * Add a new project. Handles both the form, and processing.
    */
    public function add()
    {
        $this->config->set('page_title', 'Add Project');
        $this->requireAdmin();

        $method = $this->request->getMethod();

        $pub = null;
        $values = $this->getParams();

        if ($method != 'POST') {
            $sshKey = new SshKey();
            $key = $sshKey->generate();

            $values['key']    = $key['private_key'];
            $values['pubkey'] = $key['public_key'];
            $pub = $key['public_key'];
        }

        $form = $this->projectForm($values);

        if ($method != 'POST' || ($method == 'POST' && !$form->validate())) {
            $view           = new b8\View('ProjectForm');
            $view->type     = 'add';
            $view->project  = null;
            $view->form     = $form;
            $view->key      = $pub;

            return $view->render();
        } else {
            return $this->addProject($form);
        }
    }

    protected function addProject(Form $form)
    {
        $values = $form->getValues();

        $matches = array();
        if ($values['type'] == "gitlab" && preg_match('`^(.*)@(.*):(.*)/(.*)\.git`', $values['reference'], $matches)) {
            $info = array();
            $info['user'] = $matches[1];
            $info['domain'] = $matches[2];
            $values['access_information'] = serialize($info);
            $values['reference'] = $matches[3]."/".$matches[4];
        }

        $values['ssh_private_key']  = $values['key'];
        $values['ssh_public_key']  = $values['pubkey'];

        $project = new Project();
        $project->setValues($values);

        $project = $this->projectStore->save($project);

        header('Location: '.PHPCI_URL.'project/view/' . $project->getId());
        die;
    }

    /**
    * Edit a project. Handles both the form and processing.
    */
    public function edit($projectId)
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new ForbiddenException('You do not have permission to do that.');
        }

        $method = $this->request->getMethod();
        $project = $this->projectStore->getById($projectId);

        if (empty($project)) {
            throw new NotFoundException('Project with id: ' . $projectId . ' not found');
        }

        $this->config->set('page_title', 'Edit: ' . $project->getTitle());

        $values = $project->getDataArray();
        $values['key'] = $values['ssh_private_key'];
        $values['pubkey'] = $values['ssh_public_key'];

        if ($values['type'] == "gitlab") {
            $accessInfo = $project->getAccessInformation();
            $reference = $accessInfo["user"].'@'.$accessInfo["domain"].':' . $project->getReference().".git";
            $values['reference'] = $reference;
        }

        if ($method == 'POST') {
            $values = $this->getParams();
        }

        $form = $this->projectForm($values, 'edit/' . $projectId);

        if ($method != 'POST' || ($method == 'POST' && !$form->validate())) {
            $view           = new b8\View('ProjectForm');
            $view->type     = 'edit';
            $view->project  = $project;
            $view->form     = $form;
            $view->key      = null;

            return $view->render();
        }

        $values             = $form->getValues();
        $values['ssh_private_key']  = $values['key'];
        $values['ssh_public_key']  = $values['pubkey'];

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

        $field = Form\Element\Select::create('type', 'Where is your project hosted?', true);
        $field->setPattern('^(github|bitbucket|gitlab|remote|local|hg)');
        $field->setOptions($options);
        $field->setClass('form-control')->setContainerClass('form-group');
        $form->addField($field);

        $container = new Form\ControlGroup('github-container');
        $container->setClass('github-container');

        $field = Form\Element\Select::create('github', 'Choose a Github repository:', false);
        $field->setClass('form-control')->setContainerClass('form-group');
        $container->addField($field);
        $form->addField($container);

        $field = Form\Element\Text::create('reference', 'Repository Name / URL (Remote) or Path (Local)', true);
        $field->setValidator($this->getReferenceValidator($values));
        $field->setClass('form-control')->setContainerClass('form-group');
        $form->addField($field);

        $field = Form\Element\Text::create('title', 'Project Title', true);
        $field->setClass('form-control')->setContainerClass('form-group');
        $form->addField($field);

        $title = 'Private key to use to access repository (leave blank for local and/or anonymous remotes)';
        $field = Form\Element\TextArea::create('key', $title, false);
        $field->setClass('form-control')->setContainerClass('form-group');
        $field->setRows(6);
        $form->addField($field);

        $label = 'PHPCI build config for this project (if you cannot add a phpci.yml file in the project repository)';
        $field = Form\Element\TextArea::create('build_config', $label, false);
        $field->setClass('form-control')->setContainerClass('form-group');
        $field->setRows(6);
        $form->addField($field);

        $field = Form\Element\Text::create('branch', 'Default branch name', true);
        $field->setValidator($this->getReferenceValidator($values));
        $field->setClass('form-control')->setContainerClass('form-group')->setValue('master');
        $form->addField($field);

        $label = 'Enable public status page and image for this project?';
        $field = Form\Element\Checkbox::create('allow_public_status', $label, false);
        $field->setContainerClass('form-group');
        $field->setCheckedValue(1);
        $field->setValue(1);
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
    protected function githubRepositories()
    {
        $github = new Github();
        die(json_encode($github->getRepositories()));
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
