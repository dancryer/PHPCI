<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Command;

use b8\Store\Factory;
use b8\HttpClient;
use Monolog\Logger;
use PHPCI\Helper\Lang;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;
use PHPCI\Model\Build;

/**
 * Run console command - Poll github for latest commit id
 * @author       Jimmy Cleuren <jimmy.cleuren@gmail.com>
 * @package      PHPCI
 * @subpackage   Console
 */
class PollCommand extends Command
{
    /**
     * @var \Monolog\Logger
     */
    protected $logger;

    public function __construct(Logger $logger, $name = null)
    {
        parent::__construct($name);
        $this->logger = $logger;
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
        $parser = new Parser();
        $yaml = file_get_contents(APPLICATION_PATH . 'PHPCI/config.yml');
        $this->settings = $parser->parse($yaml);

        $token = $this->settings['phpci']['github']['token'];

        if (!$token) {
            $this->logger->error(Lang::get('no_token'));
            return;
        }

        $buildStore = Factory::getStore('Build');

        $this->logger->addInfo(Lang::get('finding_projects'));
        $projectStore = Factory::getStore('Project');
        $result = $projectStore->getWhere();
        $this->logger->addInfo(Lang::get('found_n_projects', count($result['items'])));

        foreach ($result['items'] as $project) {
            $http = new HttpClient('https://api.github.com');
            $commits = $http->get('/repos/' . $project->getReference() . '/commits', array('access_token' => $token));

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
                $buildStore->save($build);

                $project->setLastCommit($last_commit);
                $projectStore->save($project);
            }
        }

        $this->logger->addInfo(Lang::get('finished_processing_builds'));
    }
}
