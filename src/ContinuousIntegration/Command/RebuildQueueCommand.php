<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Command;

use b8\Config;
use b8\Store\Factory;
use Monolog\Logger;
use Kiboko\Component\ContinuousIntegration\BuildFactory;
use Kiboko\Component\ContinuousIntegration\Helper\Lang;
use Kiboko\Component\ContinuousIntegration\Logging\OutputLogHandler;
use Kiboko\Component\ContinuousIntegration\Service\BuildService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Console
*/
class RebuildQueueCommand extends Command
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param \Monolog\Logger $logger
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
            ->setName('phpci:rebuild-queue')
            ->setDescription('Rebuilds the PHPCI worker queue.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        // For verbose mode we want to output all informational and above
        // messages to the symphony output interface.
        if ($input->hasOption('verbose') && $input->getOption('verbose')) {
            $this->logger->pushHandler(
                new OutputLogHandler($this->output, Logger::INFO)
            );
        }

        $store = Factory::getStore('Build');
        $result = $store->getByStatus(0);

        $this->logger->addInfo(Lang::get('found_n_builds', count($result['items'])));

        $buildService = new BuildService($store);

        while (count($result['items'])) {
            $build = array_shift($result['items']);
            $build = BuildFactory::getBuild($build);

            $this->logger->addInfo('Added build #' . $build->getId() . ' to queue.');
            $buildService->addBuildToQueue($build);
        }
    }
}
