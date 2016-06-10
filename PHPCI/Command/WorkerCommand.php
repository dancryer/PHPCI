<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Command;

use b8\Config;
use Monolog\Logger;
use PHPCI\Logging\OutputLogHandler;
use PHPCI\Worker\BuildWorker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
* Worker Command - Starts the BuildWorker, which pulls jobs from beanstalkd
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Console
*/
class WorkerCommand extends Command
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
            ->setName('phpci:worker')
            ->setDescription('Runs the PHPCI build worker.')
            ->addOption('debug', null, null, 'Run PHPCI in Debug Mode');
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

        // Allow PHPCI to run in "debug mode"
        if ($input->hasOption('debug') && $input->getOption('debug')) {
            $output->writeln('<comment>Debug mode enabled.</comment>');
            define('PHPCI_DEBUG_MODE', true);
        }

        $config = Config::getInstance()->get('phpci.worker', []);

        if (empty($config['host']) || empty($config['queue'])) {
            $error = 'The worker is not configured. You must set a host and queue in your config.yml file.';
            throw new \Exception($error);
        }

        $worker = new BuildWorker($config['host'], $config['queue']);
        $worker->setLogger($this->logger);
        $worker->setMaxJobs(Config::getInstance()->get('phpci.worker.max_jobs', -1));
        $worker->startWorker();
    }
}
