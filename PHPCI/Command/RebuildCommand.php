<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */
namespace PHPCI\Command;

use b8\Store\Factory;
use Monolog\Logger;
use PHPCI\Service\BuildService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Re-runs the last run build.
 *
 * @author       Dan Cryer <dan@block8.co.uk>
 */
class RebuildCommand extends Command
{
    /**
     * @type Logger
     */
    protected $logger;

    /**
     * @type OutputInterface
     */
    protected $output;

    /**
     * @type bool
     */
    protected $run;

    /**
     * @type int
     */
    protected $sleep;

    /**
     * @param \Monolog\Logger $logger
     * @param string          $name
     */
    public function __construct(Logger $logger, $name = null)
    {
        parent::__construct($name);
        $this->logger = $logger;
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
        $runner = new RunCommand($this->logger);
        $runner->setMaxBuilds(1);
        $runner->setDaemon(false);

        /** @type \PHPCI\Store\BuildStore $store */
        $store   = Factory::getStore('Build');
        $service = new BuildService($store);

        $builds    = $store->getLatestBuilds(null, 1);
        $lastBuild = array_shift($builds);
        $service->createDuplicateBuild($lastBuild);

        $runner->run(new ArgvInput([]), $output);
    }

    /**
     * Called when log entries are made in Builder / the plugins.
     *
     * @see \PHPCI\Builder::log()
     */
    public function logCallback($log)
    {
        $this->output->writeln($log);
    }
}
