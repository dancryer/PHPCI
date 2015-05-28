<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Command;

use PHPCI\Helper\Lang;
use PHPCI\Service\BuildService;
use PHPCI\Store\ProjectStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create build command - creates a build for a project
 * @author       Jérémy DECOOL (@jdecool)
 * @package      PHPCI
 * @subpackage   Console
 */
class CreateBuildCommand extends Command
{
    /**
     * @var ProjectStore
     */
    protected $projectStore;

    /**
     * @var BuildService
     */
    protected $buildService;

    /**
     * @param ProjectStore $projectStore
     */
    public function __construct(ProjectStore $projectStore, BuildService $buildService)
    {
        parent::__construct();

        $this->projectStore = $projectStore;
        $this->buildService = $buildService;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('phpci:create-build')
            ->setDescription(Lang::get('create_build_project'))
            ->addArgument('projectId', InputArgument::REQUIRED, Lang::get('project_id_argument'))
            ->addOption('commit', null, InputOption::VALUE_OPTIONAL, Lang::get('commit_id_option'))
            ->addOption('branch', null, InputOption::VALUE_OPTIONAL, Lang::get('branch_name_option'));
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $projectId = $input->getArgument('projectId');
        $commitId = $input->getOption('commit');
        $branch = $input->getOption('branch');

        $project = $this->projectStore->getById($projectId);
        if (empty($project)) {
            throw new \InvalidArgumentException('Project does not exist: ' . $projectId);
        }

        try {
            $this->buildService->createBuild($project, $commitId, $branch);
            $output->writeln(Lang::get('build_created'));
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', Lang::get('failed')));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
    }
}
