<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Controller;

use b8;
use b8\Form;
use b8\Store;
use Kiboko\Component\ContinuousIntegration\Controller;
use Kiboko\Component\ContinuousIntegration\Model\ProjectGroup;

/**
 * Project Controller - Allows users to create, edit and view projects.
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      Kiboko\Component\ContinuousIntegration
 * @subpackage   Web
 */
class GroupController extends Controller
{
    /**
     * @var \Kiboko\Component\ContinuousIntegration\Store\ProjectGroupStore
     */
    protected $groupStore;

    /**
     * Set up this controller.
     */
    public function init()
    {
        $this->groupStore = b8\Store\Factory::getStore('ProjectGroup');
    }

    /**
     * List project groups.
     */
    public function index()
    {
        $this->requireAdmin();

        $groups = array();
        $groupList = $this->groupStore->getWhere(array(), 100, 0, array(), array('title' => 'ASC'));

        foreach ($groupList['items'] as $group) {
            $thisGroup = array(
                'title' => $group->getTitle(),
                'id' => $group->getId(),
            );
            $projects = b8\Store\Factory::getStore('Project')->getByGroupId($group->getId());
            $thisGroup['projects'] = $projects['items'];
            $groups[] = $thisGroup;
        }

        $this->view->groups = $groups;
    }

    /**
     * Add or edit a project group.
     * @param null $groupId
     * @return void|b8\Http\Response\RedirectResponse
     */
    public function edit($groupId = null)
    {
        $this->requireAdmin();

        if (!is_null($groupId)) {
            $group = $this->groupStore->getById($groupId);
        } else {
            $group = new ProjectGroup();
        }

        if ($this->request->getMethod() == 'POST') {
            $group->setTitle($this->getParam('title'));
            $this->groupStore->save($group);

            $response = new b8\Http\Response\RedirectResponse();
            $response->setHeader('Location', KIBOKO_CI_APP_URL.'group');
            return $response;
        }

        $form = new Form();
        $form->setMethod('POST');
        $form->setAction(KIBOKO_CI_APP_URL . 'group/edit' . (!is_null($groupId) ? '/' . $groupId : ''));

        $title = new Form\Element\Text('title');
        $title->setContainerClass('form-group');
        $title->setClass('form-control');
        $title->setLabel('Group Title');
        $title->setValue($group->getTitle());

        $submit = new Form\Element\Submit();
        $submit->setValue('Save Group');

        $form->addField($title);
        $form->addField($submit);

        $this->view->form = $form;
    }

    /**
     * Delete a project group.
     * @param $groupId
     * @return b8\Http\Response\RedirectResponse
     */
    public function delete($groupId)
    {
        $this->requireAdmin();
        $group = $this->groupStore->getById($groupId);

        $this->groupStore->delete($group);
        $response = new b8\Http\Response\RedirectResponse();
        $response->setHeader('Location', KIBOKO_CI_APP_URL.'group');
        return $response;
    }
}
