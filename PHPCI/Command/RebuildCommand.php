<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Command;

use Monolog\Logger;
use PHPCI\BuildFactory;
use PHPCI\Command\RunCommand;
use PHPCI\Service\BuildService;
use PHPCI\Store\BuildStore;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
* Re-runs the last run build.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Console
*/
class RebuildCommand extends RunCommand
{
    /**
     * @var BuildService
     */
    protected $service;


    public function __construct(
        Logger $logger,
        BuildStore $store = null,
        BuildFactory $factory = null,
        $timeout = null,
        BuildService $service = null,
        $name = null
    ) {
        parent::__construct($logger, $store, $factory, $timeout, $name);

        $this->service = $service ? $service : new BuildService($this->store);
    }

    protected function configure()
    {
        $this
            ->setName('phpci:rebuild')
            ->setDescription('Re-runs the last run build.');
    }

    /**
    * Duplicates the last build and runs it.
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lastBuild = array_shift($this->store->getLatestBuilds(null, 1));
        $build = $this->service->createDuplicateBuild($lastBuild);

        $this->runBuild($build);
    }
}
