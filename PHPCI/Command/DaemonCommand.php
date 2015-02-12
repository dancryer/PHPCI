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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
* Daemon that loops and call the run-command.
* @author       Gabriel Baker <gabriel.baker@autonomicpilot.co.uk>
* @package      PHPCI
* @subpackage   Console
*/
class DaemonCommand extends Command
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
            ->setName('phpci:daemon')
            ->setDescription('Initiates the daemon to run commands.')
            ->addArgument(
                'state',
                InputArgument::REQUIRED,
                'start|stop|status'
            );
    }

    /**
    * Loops through running.
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $state = $input->getArgument('state');

        switch ($state) {
            case 'start':
                $this->startDaemon();
                break;
            case 'stop':
                $this->stopDaemon();
                break;
            case 'status':
                $this->statusDaemon();
                break;
            default:
                echo "Not a valid choice, please use start stop or status";
                break;
        }

    }

    protected function startDaemon()
    {

        if (file_exists(PHPCI_DIR.'/daemon/daemon.pid')) {
            echo "Already started\n";
            $this->logger->warning("Daemon already started");
            return "alreadystarted";
        }

        $logfile = PHPCI_DIR."/daemon/daemon.log";
        $cmd = "nohup %s/daemonise phpci:daemonise > %s 2>&1 &";
        $command = sprintf($cmd, PHPCI_DIR, $logfile);
        $this->logger->info("Daemon started");
        exec($command);
    }

    protected function stopDaemon()
    {

        if (!file_exists(PHPCI_DIR.'/daemon/daemon.pid')) {
            echo "Not started\n";
            $this->logger->warning("Can't stop daemon as not started");
            return "notstarted";
        }

        $cmd = "kill $(cat %s/daemon/daemon.pid)";
        $command = sprintf($cmd, PHPCI_DIR);
        exec($command);
        $this->logger->info("Daemon stopped");
        unlink(PHPCI_DIR.'/daemon/daemon.pid');
    }

    protected function statusDaemon()
    {

        if (!file_exists(PHPCI_DIR.'/daemon/daemon.pid')) {
            echo "Not running\n";
            return "notrunning";
        }

        $pid = trim(file_get_contents(PHPCI_DIR.'/daemon/daemon.pid'));
        $pidcheck = sprintf("/proc/%s", $pid);
        if (is_dir($pidcheck)) {
            echo "Running\n";
            return "running";
        }

        unlink(PHPCI_DIR.'/daemon/daemon.pid');
        echo "Not running\n";
        return "notrunning";
    }
}
