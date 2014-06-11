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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
            ->setDescription('Poll github to check if we need to start a build.');
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
            $this->logger->error("No github token found");
            exit();
        }

        $buildStore = Factory::getStore('Build');

        $this->logger->addInfo("Finding projects to poll");
        $projectStore = Factory::getStore('Project');
        $result = $projectStore->getWhere();
        $this->logger->addInfo(sprintf("Found %d projects", count($result['items'])));

        foreach ($result['items'] as $project) {
            $http = new HttpClient('https://api.github.com');
            $commits = $http->get('/repos/' . $project->getReference() . '/commits', array('access_token' => $token));

            $last_commit = $commits['body'][0]['sha'];
            $last_committer = $commits['body'][0]['commit']['committer']['email'];

            $this->logger->info("Last commit to github for " . $project->getTitle() . " is " . $last_commit);

            if ($project->getLastCommit() != $last_commit && $last_commit != "") {
                $this->logger->info(
                    "Last commit is different from database, adding new build for " . $project->getTitle()
                );

                $build = new Build();
                $build->setProjectId($project->getId());
                $build->setCommitId($last_commit);
                $build->setStatus(Build::STATUS_NEW);
                $build->setBranch($project->getBranch());
                $build->setCreated(new \DateTime());
                if (!empty($last_committer)) {
                    $build->setCommitterEmail($last_committer);
                }
                $buildStore->save($build);

                $project->setLastCommit($last_commit);
                $projectStore->save($project);
            }
        }

        $this->logger->addInfo("Finished processing builds");
    }
}
