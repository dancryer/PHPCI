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
use b8\Exception\HttpException\NotFoundException;
use b8\Store;
use PHPCI;
use PHPCI\BuildFactory;
use PHPCI\Helper\Github;
use PHPCI\Helper\Lang;
use PHPCI\Helper\SshKey;
use PHPCI\Service\BuildService;
use PHPCI\Service\ProjectService;

/**
* Project Controller - Allows users to create, edit and view projects.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Web
*/
class ProjectController extends PHPCI\Controller
{
    /**
     * @var \PHPCI\Store\ProjectStore
     */
    protected $projectStore;

    /**
     * @var \PHPCI\Service\ProjectService
     */
    protected $projectService;

    /**
     * @var \PHPCI\Store\BuildStore
     */
    protected $buildStore;

    /**
     * @var \PHPCI\Service\BuildService
     */
    protected $buildService;

    /**
     * Initialise the controller, set up stores and services.
     */
    public function init()
    {
        $this->buildStore = Store\Factory::getStore('Build');
        $this->projectStore = Store\Factory::getStore('Project');
        $this->projectService = new ProjectService($this->projectStore);
        $this->buildService = new BuildService($this->buildStore);
    }

    /**
    * View a specific project.
    */
    public function view($projectId)
    {
        $branch = $this->getParam('branch', '');
        $project = $this->projectStore->getById($projectId);

        if (empty($project)) {
            throw new NotFoundException(Lang::get('project_x_not_found', $projectId));
        }

        $per_page = 10;
        $page     = $this->getParam('p', 1);
        $builds   = $this->getLatestBuildsHtml($projectId, urldecode($branch), (($page - 1) * $per_page));
        $pages    = $builds[1] == 0 ? 1 : ceil($builds[1] / $per_page);

        if ($page > $pages) {
            $response = new b8\Http\Response\RedirectResponse();
            $response->setHeader('Location', PHPCI_URL.'project/view/'.$projectId);
            return $response;
        }

        $this->view->builds   = $builds[0];
        $this->view->total    = $builds[1];
        $this->view->project  = $project;
        $this->view->branch = urldecode($branch);
        $this->view->branches = $this->projectStore->getKnownBranches($projectId);
        $this->view->page     = $page;
        $this->view->pages    = $pages;

        $this->layout->title = $project->getTitle();
        $this->layout->subtitle = $this->view->branch;

        return $this->view->render();
    }

    /**
    * Create a new pending build for a project.
    */
    public function build($projectId, $branch = '')
    {
        /* @var \PHPCI\Model\Project $project */
        $project = $this->projectStore->getById($projectId);

        if (empty($branch)) {
            $branch = $project->getBranch();
        }

        if (empty($project)) {
            throw new NotFoundException(Lang::get('project_x_not_found', $projectId));
        }

        $email = $_SESSION['phpci_user']->getEmail();
        $build = $this->buildService->createBuild($project, null, urldecode($branch), $email);

        $response = new b8\Http\Response\RedirectResponse();
        $response->setHeader('Location', PHPCI_URL.'build/view/' . $build->getId());
        return $response;
    }

    /**
    * Delete a project.
    */
    public function delete($projectId)
    {
        $this->requireAdmin();

        $project = $this->projectStore->getById($projectId);
        $this->projectService->deleteProject($project);

        $response = new b8\Http\Response\RedirectResponse();
        $response->setHeader('Location', PHPCI_URL);
        return $response;
    }

    /**
    * AJAX get latest builds.
    */
    public function builds($projectId)
    {
        $branch = $this->getParam('branch', '');
        $builds = $this->getLatestBuildsHtml($projectId, urldecode($branch));

        $this->response->disableLayout();
        $this->response->setContent($builds[0]);
        return $this->response;
    }

    /**
     * Render latest builds for project as HTML table.
     *
     * @param $projectId
     * @param string $branch A urldecoded branch name.
     * @param int $start
     * @return array
     */
    protected function getLatestBuildsHtml($projectId, $branch = '', $start = 0)
    {
        $criteria = array('project_id' => $projectId);
        if (!empty($branch)) {
            $criteria['branch'] = $branch;
        }

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
        $this->layout->title = Lang::get('add_project');
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
            $title = $this->getParam('title', 'New Project');
            $reference = $this->getParam('reference', null);
            $type = $this->getParam('type', null);

            $options = array(
                'ssh_private_key' => $this->getParam('key', null),
                'ssh_public_key' => $this->getParam('pubkey', null),
                'build_config' => $this->getParam('build_config', null),
                'allow_public_status' => $this->getParam('allow_public_status', 0),
                'branch' => $this->getParam('branch', null),
            );

            $project = $this->projectService->createProject($title, $type, $reference, $options);

            $response = new b8\Http\Response\RedirectResponse();
            $response->setHeader('Location', PHPCI_URL.'project/view/' . $project->getId());
            return $response;
        }
    }

    /**
    * Edit a project. Handles both the form and processing.
    */
    public function edit($projectId)
    {
        $this->requireAdmin();

        $method = $this->request->getMethod();
        $project = $this->projectStore->getById($projectId);

        if (empty($project)) {
            throw new NotFoundException(Lang::get('project_x_not_found', $projectId));
        }

        $this->layout->title = $project->getTitle();
        $this->layout->subtitle = Lang::get('edit_project');

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
            $view->key      = $values['pubkey'];

            return $view->render();
        }

        $title = $this->getParam('title', Lang::get('new_project'));
        $reference = $this->getParam('reference', null);
        $type = $this->getParam('type', null);

        $options = array(
            'ssh_private_key' => $this->getParam('key', null),
            'ssh_public_key' => $this->getParam('pubkey', null),
            'build_config' => $this->getParam('build_config', null),
            'allow_public_status' => $this->getParam('allow_public_status', 0),
            'branch' => $this->getParam('branch', null),
        );

        $project = $this->projectService->updateProject($project, $title, $type, $reference, $options);

        $response = new b8\Http\Response\RedirectResponse();
        $response->setHeader('Location', PHPCI_URL.'project/view/' . $project->getId());
        return $response;
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
            'choose' => Lang::get('select_repository_type'),
            'github' => Lang::get('github'),
            'bitbucket' => Lang::get('bitbucket'),
            'gitlab' => Lang::get('gitlab'),
            'remote' => Lang::get('remote'),
            'local' => Lang::get('local'),
            'hg'    => Lang::get('hg'),
            'svn'    => Lang::get('svn'),
            );

        $field = Form\Element\Select::create('type', Lang::get('where_hosted'), true);
        $field->setPattern('^(github|bitbucket|gitlab|remote|local|hg|svn)');
        $field->setOptions($options);
        $field->setClass('form-control')->setContainerClass('form-group');
        $form->addField($field);

        $container = new Form\ControlGroup('github-container');
        $container->setClass('github-container');

        $field = Form\Element\Select::create('github', Lang::get('choose_github'), false);
        $field->setClass('form-control')->setContainerClass('form-group');
        $container->addField($field);
        $form->addField($container);

        $field = Form\Element\Text::create('reference', Lang::get('repo_name'), true);
        $field->setValidator($this->getReferenceValidator($values));
        $field->setClass('form-control')->setContainerClass('form-group');
        $form->addField($field);

        $field = Form\Element\Text::create('title', Lang::get('project_title'), true);
        $field->setClass('form-control')->setContainerClass('form-group');
        $form->addField($field);

        $field = Form\Element\TextArea::create('key', Lang::get('project_private_key'), false);
        $field->setClass('form-control')->setContainerClass('form-group');
        $field->setRows(6);
        $form->addField($field);

        $field = Form\Element\TextArea::create('build_config', Lang::get('build_config'), false);
        $field->setClass('form-control')->setContainerClass('form-group');
        $field->setRows(6);
        $form->addField($field);

        $field = Form\Element\Text::create('branch', Lang::get('default_branch'), true);
        $field->setClass('form-control')->setContainerClass('form-group')->setValue('master');
        $form->addField($field);

        $field = Form\Element\Checkbox::create('allow_public_status', Lang::get('allow_public_status'), false);
        $field->setContainerClass('form-group');
        $field->setCheckedValue(1);
        $field->setValue(0);
        $form->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue(Lang::get('save_project'));
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

        $response = new b8\Http\Response\JsonResponse();
        $response->setContent($github->getRepositories());
        return $response;
    }

    /**
     * Get the validator to use to check project references.
     * @param $values
     * @return callable
     */
    protected function getReferenceValidator($values)
    {
        return function ($val) use ($values) {
            $type = $values['type'];

            $validators = array(
                'hg' => array(
                    'regex' => '/^(https?):\/\//',
                    'message' => Lang::get('error_mercurial')
                ),
                'remote' => array(
                    'regex' => '/^(git|https?):\/\//',
                    'message' => Lang::get('error_remote')
                ),
                'gitlab' => array(
                    'regex' => '`^(.*)@(.*):(.*)/(.*)\.git`',
                    'message' => Lang::get('error_gitlab')
                ),
                'github' => array(
                    'regex' => '/^[a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-\.]+$/',
                    'message' => Lang::get('error_github')
                ),
                'bitbucket' => array(
                    'regex' => '/^[a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-\.]+$/',
                    'message' => Lang::get('error_bitbucket')
                ),
            );

            if (in_array($type, $validators) && !preg_match($validators[$type]['regex'], $val)) {
                throw new \Exception($validators[$type]['message']);
            } elseif ($type == 'local' && !is_dir($val)) {
                throw new \Exception(Lang::get('error_path'));
            }

            return true;
        };
    }
}
