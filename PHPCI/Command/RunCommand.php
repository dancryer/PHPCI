<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Command;

use b8\Config;
use b8\Store\Factory;
use DateTime;
use Exception;
use Monolog\Logger;
use PHPCI\BuilderFactory;
use PHPCI\BuildFactory;
use PHPCI\Helper\Lang;
use PHPCI\Logging\OutputLogHandler;
use PHPCI\Model\Build;
use PHPCI\Store\BuildStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run console command - Runs any pending builds.
 *
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Console
 */
class RunCommand extends Command
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var BuilderFactory
     */
    protected $factory;

    /**
     * @var BuildStore
     */
    protected $store;

    /**
     * @var integer
     */
    protected $timeout;

    /** Initialise the runner.
     *
     * @param Logger $logger
     * @param BuildStore $store
     * @param BuildFactory $factory
     * @param int $timeout
     */
    public function __construct(
        Logger $logger,
        BuildStore $store = null,
        BuilderFactory $factory = null,
        $timeout = null,
        $name = null
    ) {
        parent::__construct($name);

        $this->logger = $logger;
        $this->store = $store ? $store : Factory::getStore('Build');
        $this->factory = $factory ? $factory : new BuilderFactory($this->logger, $this->store);
        $this->timeout = $timeout ? $timeout : Config::getInstance()->get('phpci.build.failed_after', 1800);
    }

    protected function configure()
    {
        $this
            ->setName('phpci:run-builds')
            ->setDescription(Lang::get('run_all_pending'))
            ->addOption(
                'max-builds',
                'm',
                InputOption::VALUE_REQUIRED,
                "Maximum number of builds to run.",
                100
            );
    }

    /**
     * Sets up logging.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        // For verbose mode we want to output all informational and above
        // messages to the symphony output interface.
        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->logger->pushHandler(new OutputLogHandler($output, Logger::INFO));
        }
    }

    /**
     * Pulls up to $maxBuilds pending builds from the database and runs them.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $builds = (int) $input->getOption('max-builds');

        while (($build = $this->findNextPendingBuild()) && $builds-- > 0) {
            $this->runBuild($build);
        }

        $this->logger->info(Lang::get('finished_processing_builds'));
    }

    /**
     * Find a pending build for a project which has no running builds.
     *
     * @return Build|null
     *
     * @internal
     */
    public function findNextPendingBuild()
    {
        $running = $this->validateRunningBuilds();

        $this->logger->info(Lang::get('finding_builds'));
        $result = $this->store->getByStatus(Build::STATUS_NEW);
        $this->logger->info(Lang::get('found_n_builds', count($result['items'])));

        foreach ($result['items'] as $build) {
            if (isset($running[$build->getProjectId()])) {
                $this->logger->info(Lang::get('skipping_build', $build->getId()));
            } else {
                return $build;
            }
        }
    }

    /**
     * Runs one build and cleans up after it finishes.
     *
     * @param Build $build
     *
     * @internal
     */
    public function runBuild(Build $build)
    {
        $this->logger->info(sprintf("Running build %s", $build->getId()));

        $build = BuildFactory::getBuild($build);
        $builder = $this->factory->createBuilder($build);

        try {
            $builder->execute();
        } catch (Exception $ex) {
            $build->setStatus(Build::STATUS_FAILED);
            $build->setLog($build->getLog() . PHP_EOL . PHP_EOL . $ex->getMessage());
        }

        $build->setFinished(new DateTime());
        $this->store->save($build);
        $builder->removeBuildDirectory();

        $this->logger->info(sprintf("Build %d ended", $build->getId()));
    }

    /**
     * Checks all running builds, and kills those that seem dead.
     *
     * @return array An array with project identifiers as keys, for projets
     *               that have running builds.
     *
     * @internal
     */
    public function validateRunningBuilds()
    {
        $running = $this->store->getByStatus(Build::STATUS_RUNNING);
        $rtn = array();
        $now = time();

        foreach ($running['items'] as $build) {
            /** @var Build $build */

            $start = $build->getStarted()->getTimestamp();

            if (($now - $start) > $this->timeout) {
                $this->logger->info(Lang::get('marked_as_failed', $build->getId()));
                $build->setStatus(Build::STATUS_FAILED);
                $build->setFinished(new DateTime());
                $this->store->save($build);
                // TODO: find a way to remove the directory
                continue;
            }

            $rtn[$build->getProjectId()] = true;
        }

        return $rtn;
    }
}
