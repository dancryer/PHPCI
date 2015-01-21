<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Service;

use PHPCI\Model\Project;
use PHPCI\Store\ProjectStore;

/**
 * The project service handles the creation, modification and deletion of projects.
 * Class ProjectService
 * @package PHPCI\Service
 */
class ProjectService
{
    /**
     * @var \PHPCI\Store\ProjectStore
     */
    protected $projectStore;

    /**
     * @param ProjectStore $projectStore
     */
    public function __construct(ProjectStore $projectStore)
    {
        $this->projectStore = $projectStore;
    }

    /**
     * Create a new project model and use the project store to save it.
     * @param string $title
     * @param string $type
     * @param string $reference
     * @param array $options
     * @return \PHPCI\Model\Project
     */
    public function createProject($title, $type, $reference, $options = array())
    {
        // Create base project and use updateProject() to set its properties:
        $project = new Project();
        return $this->updateProject($project, $title, $type, $reference, $options);
    }

    /**
     * Update the properties of a given project.
     * @param Project $project
     * @param string $title
     * @param string $type
     * @param string $reference
     * @param array $options
     * @return \PHPCI\Model\Project
     */
    public function updateProject(Project $project, $title, $type, $reference, $options = array())
    {
        // Set basic properties:
        $project->setTitle($title);
        $project->setType($type);
        $project->setReference($reference);
        $project->setAllowPublicStatus(0);

        // Handle extra project options:
        if (array_key_exists('ssh_private_key', $options)) {
            $project->setSshPrivateKey($options['ssh_private_key']);
        }

        if (array_key_exists('ssh_public_key', $options)) {
            $project->setSshPublicKey($options['ssh_public_key']);
        }

        if (array_key_exists('build_config', $options)) {
            $project->setBuildConfig($options['build_config']);
        }

        if (array_key_exists('allow_public_status', $options)) {
            $project->setAllowPublicStatus((int)$options['allow_public_status']);
        }

        if (array_key_exists('branch', $options)) {
            $project->setBranch($options['branch']);
        }

        // Allow certain project types to set access information:
        $this->processAccessInformation($project);

        // Save and return the project:
        return $this->projectStore->save($project);
    }

    /**
     * Delete a given project.
     * @param Project $project
     * @return bool
     */
    public function deleteProject(Project $project)
    {
        return $this->projectStore->delete($project);
    }

    /**
     * In circumstances where it is necessary, populate access information based on other project properties.
     * @see ProjectService::createProject()
     * @param Project $project
     */
    protected function processAccessInformation(Project &$project)
    {
        $matches = array();
        $reference = $project->getReference();

        if ($project->getType() == 'gitlab') {
            $info = array();

            if (preg_match('`^(.+)@(.+):([0-9]*)\/?(.+)\.git`', $reference, $matches)) {
                $info['user'] = $matches[1];
                $info['domain'] = $matches[2];
                $info['port'] = $matches[3];

                $project->setReference($matches[4]);
            }

            $project->setAccessInformation($info);
        }
    }
}
