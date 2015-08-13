<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Command;

use b8\HttpClient;
use Monolog\Logger;
use PHPCI\Helper\Lang;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PHPCI\Config;
use PHPCI\Model\Build;
use PHPCI\Store\BuildStore;
use PHPCI\Store\ProjectStore;

/**
 * Run console command - Poll github for latest commit id
 * @author       Jimmy Cleuren <jimmy.cleuren@gmail.com>
 * @package      PHPCI
 * @subpackage   Console
 */
class PollCommand extends Command
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var BuildStore
     */
    protected $buildStore;

    /**
     * @var ProjectStore
     */
    protected $projectStore;

    /**
     * @var HttpClient
     */
    protected $githubClient;

    public function __construct(Config $config, Logger $logger, BuildStore $buildStore, ProjectStore $projectStore, HttpClient $githubClient)
    {
        parent::__construct();

        $this->config = $config;
        $this->logger = $logger;
        $this->buildStore = $buildStore;
        $this->projectStore = $projectStore;
        $this->githubClient = $githubClient;
    }

    protected function configure()
    {
        $this
            ->setName('phpci:poll-github')
            ->setDescription(Lang::get('poll_github'));
    }

    /**
     * Pulls all pending builds from the database and runs them.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $token = $this->config->get('phpci.github.token');

        if (!$token) {
            $this->logger->error(Lang::get('no_token'));
            return;
        }

        $this->logger->addInfo(Lang::get('finding_projects'));
        $result = $this->projectStore->getWhere();
        $this->logger->addInfo(Lang::get('found_n_projects', count($result['items'])));

        foreach ($result['items'] as $project) {
            $commits = $this->githubClient->get('/repos/' . $project->getReference() . '/commits', array('access_token' => $token));

            $last_commit = $commits['body'][0]['sha'];
            $last_committer = $commits['body'][0]['commit']['committer']['email'];
            $message = $commits['body'][0]['commit']['message'];

            $this->logger->info(Lang::get('last_commit_is', $project->getTitle(), $last_commit));

            if ($project->getLastCommit() != $last_commit && $last_commit != "") {
                $this->logger->info(
                    Lang::get('adding_new_build')
                );

                $build = new Build();
                $build->setProjectId($project->getId());
                $build->setCommitId($last_commit);
                $build->setStatus(Build::STATUS_NEW);
                $build->setBranch($project->getBranch());
                $build->setCreated(new \DateTime());
                $build->setCommitMessage($message);
                if (!empty($last_committer)) {
                    $build->setCommitterEmail($last_committer);
                }
                $this->buildStore->save($build);

                $project->setLastCommit($last_commit);
                $this->projectStore->save($project);
            }
        }

        $this->logger->addInfo(Lang::get('finished_processing_builds'));
    }
}
