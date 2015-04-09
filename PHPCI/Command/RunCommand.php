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
use Monolog\Logger;
use PHPCI\Builder;
use PHPCI\BuildFactory;
use PHPCI\Helper\Lang;
use PHPCI\Logging\BuildDBLogHandler;
use PHPCI\Logging\LoggedBuildContextTidier;
use PHPCI\Logging\OutputLogHandler;
use PHPCI\Model\Build;
use PHPCI\Store\BuildStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
* Run console command - Runs any pending builds.
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
     * @param Logger $logger
     * @param string $name
     */
    public function __construct(Logger $logger, $name = null)
    {
        parent::__construct($name);
        $this->logger = $logger;
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

        $this->logger->pushProcessor(new LoggedBuildContextTidier());

        // For verbose mode we want to output all informational and above
        // messages to the symphony output interface.
        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->logger->pushHandler(
                new OutputLogHandler($output, Logger::INFO)
            );
        }
    }

    /**
     * Pulls up to $maxBuilds pending builds from the database and runs them.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $builds = (int)$input->getOption('max-builds');

        do {
            $build = $this->findNextBuild();
            if ($build) {
                $this->runBuild($build);
                $builds--;
            }
        } while ($build && $builds > 0);

        $this->logger->info(Lang::get('finished_processing_builds'));
    }

    /**
     * Find a pending build for a project which has no running builds.
     *
     * @return Build|null
     */
    protected function findNextBuild()
    {
        $running = $this->validateRunningBuilds();

        $this->logger->info(Lang::get('finding_builds'));
        $result = Factory::getStore('Build')->getByStatus(Build::STATUS_NEW);
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
     */
    protected function runBuild(Build $build)
    {
        $build = BuildFactory::getBuild($build);
        $this->logger->info(sprintf("Running build %s", $build->getId()));

        $buildDbLog = new BuildDBLogHandler($build, Logger::INFO);
        $this->logger->pushHandler($buildDbLog);

        try {
            $builder = new Builder($build, $this->logger);
            $builder->execute();
        } catch (\Exception $ex) {
            $build->setStatus(Build::STATUS_FAILED);
            $build->setFinished(new \DateTime());
            $build->setLog($build->getLog() . PHP_EOL . PHP_EOL . $ex->getMessage());
            Factory::getStore('Build')->save($build);
        }

        $this->removeBuildDirectory($build);
        $this->logger->popHandler($buildDbLog);
        $this->logger->info(sprintf("Build %d ended", $build->getId()));
    }

    /**
     * Checks all running builds, and kills those that seem dead.
     *
     * @return array An array with project identifiers as keys, for projets
     *               that have running builds.
     */
    protected function validateRunningBuilds()
    {
        /** @var BuildStore $store */
        $store = Factory::getStore('Build');
        $running = $store->getByStatus(1);
        $rtn = array();

        $timeout = Config::getInstance()->get('phpci.build.failed_after', 1800);

        foreach ($running['items'] as $build) {
            /** @var Build $build */
            $build = BuildFactory::getBuild($build);

            $now = time();
            $start = $build->getStarted()->getTimestamp();

            if (($now - $start) > $timeout) {
                $this->logger->info(Lang::get('marked_as_failed', $build->getId()));
                $build->setStatus(Build::STATUS_FAILED);
                $build->setFinished(new \DateTime());
                $store->save($build);
                $this->removeBuildDirectory($build);
                continue;
            }

            $rtn[$build->getProjectId()] = true;
        }

        return $rtn;
    }

    /**
     * Remove the build directory of a finished build.
     *
     * @param Build $build
     *
     * @todo Move this to the Build class.
     */
    protected function removeBuildDirectory(Build $build)
    {
        $buildPath = PHPCI_DIR . 'PHPCI/build/' . $build->getId() . '/';

        if (is_dir($buildPath)) {
            $cmd = 'rm -Rf "%s"';

            if (IS_WIN) {
                $cmd = 'rmdir /S /Q "%s"';
            }

            shell_exec($cmd);
        }
    }
}
