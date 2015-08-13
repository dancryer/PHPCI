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
use Monolog\Logger;
use PHPCI\Service\BuildService;
use PHPCI\Store\BuildStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
* Re-runs the last run build.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Console
*/
class RebuildCommand extends Command
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var boolean
     */
    protected $run;

    /**
     * @var int
     */
    protected $sleep;

    /**
     * @param BuildStore
     */
    protected $buildStore;

    /**
     * @param BuildService
     */
    protected $buildService;

    /**
     * @param RunCommand
     */
    protected $runCommand;

    public function __construct(Logger $logger, BuildStore $buildStore, BuildService $buildService, RunCommand $runCommand)
    {
        parent::__construct();

        $this->logger = $logger;
        $this->buildStore = $buildStore;
        $this->buildService = $buildService;
        $this->runCommand = $runCommand;
    }

    protected function configure()
    {
        $this
            ->setName('phpci:rebuild')
            ->setDescription('Re-runs the last run build.');
    }

    /**
    * Loops through running.
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->runCommand->setMaxBuilds(1);
        $this->runCommand->setDaemon(false);

        $builds = $this->buildStore->getLatestBuilds(null, 1);
        $lastBuild = array_shift($builds);
        $this->buildService->createDuplicateBuild($lastBuild);

        $runner->run(new ArgvInput(array()), $output);
    }

    /**
    * Called when log entries are made in Builder / the plugins.
    * @see \PHPCI\Builder::log()
    */
    public function logCallback($log)
    {
        $this->output->writeln($log);
    }
}
