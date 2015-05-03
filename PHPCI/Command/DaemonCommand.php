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
use PHPCI\ProcessControl\Factory;
use PHPCI\ProcessControl\ProcessControlInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $pidFilePath;

    /**
     * @var string
     */
    protected $logFilePath;

    /**
     * @var ProcessControlInterface
     */
    protected $processControl;

    public function __construct(Logger $logger, ProcessControlInterface $processControl = null, $name = null)
    {
        parent::__construct($name);
        $this->logger = $logger;
        $this->processControl = $processControl ?: Factory::getInstance();
    }

    protected function configure()
    {
        $this
            ->setName('phpci:daemon')
            ->setDescription('Initiates the daemon to run commands.')
            ->addArgument(
                'state', InputArgument::REQUIRED, 'start|stop|status'
            )
            ->addOption(
                'pid-file', 'p', InputOption::VALUE_REQUIRED,
                'Path of the PID file',
                implode(DIRECTORY_SEPARATOR,
                    array(PHPCI_DIR, 'daemon', 'daemon.pid'))
            )
            ->addOption(
                'log-file', 'l', InputOption::VALUE_REQUIRED,
                'Path of the log file',
                implode(DIRECTORY_SEPARATOR,
                    array(PHPCI_DIR, 'daemon', 'daemon.log'))
        );
    }

    /**
     * Loops through running.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->pidFilePath = $input->getOption('pid-file');
        $this->logFilePath = $input->getOption('log-file');

        $state = $input->getArgument('state');

        switch ($state) {
            case 'start':
                $this->startDaemon();
                break;
            case 'stop':
                $this->stopDaemon();
                break;
            case 'status':
                $this->statusDaemon($output);
                break;
            default:
                $this->output->writeln("<error>Not a valid choice, please use start, stop or status</error>");
                break;
        }
    }

    protected function startDaemon()
    {
        $pid = $this->getRunningPid();
        if ($pid) {
            $this->logger->notice("Daemon already started", array('pid' => $pid));
            return "alreadystarted";
        }

        $this->logger->info("Trying to start the daemon");

        $cmd = "nohup %s/daemonise phpci:daemonise > %s 2>&1 &";
        $command = sprintf($cmd, PHPCI_DIR, $this->logFilePath);
        $output = $exitCode = null;
        exec($command, $output, $exitCode);

        if ($exitCode !== 0) {
            $this->logger->error(sprintf("daemonise exited with status %d", $exitCode));
            return "notstarted";
        }

        for ($i = 0; !($pid = $this->getRunningPid()) && $i < 5; $i++) {
            sleep(1);
        }

        if (!$pid) {
            $this->logger->error("Could not start the daemon");
            return "notstarted";
        }

        $this->logger->notice("Daemon started", array('pid' => $pid));
        return "started";
    }

    protected function stopDaemon()
    {
        $pid = $this->getRunningPid();
        if (!$pid) {
            $this->logger->notice("Cannot stop the daemon as it is not started");
            return "notstarted";
        }

        $this->logger->info("Trying to terminate the daemon", array('pid' => $pid));
        $this->processControl->kill($pid);

        for ($i = 0; ($pid = $this->getRunningPid()) && $i < 5; $i++) {
            sleep(1);
        }

        if ($pid) {
            $this->logger->warning("The daemon is resiting, trying to kill it", array('pid' => $pid));
            $this->processControl->kill($pid, true);

            for ($i = 0; ($pid = $this->getRunningPid()) && $i < 5; $i++) {
                sleep(1);
            }
        }

        if (!$pid) {
            $this->logger->notice("Daemon stopped");
            return "stopped";
        }

        $this->logger->error("Could not stop the daemon");
    }

    protected function statusDaemon(OutputInterface $output)
    {
        $pid = $this->getRunningPid();
        if ($pid) {
            $output->writeln(sprintf('The daemon is running, PID: %d', $pid));
            return "running";
        }

        $output->writeln('The daemon is not running');
        return "notrunning";
    }

    /** Check if there is a running daemon
     *
     * @return int|null
     */
    protected function getRunningPid()
    {
        if (!file_exists($this->pidFilePath)) {
            return;
        }

        $pid = intval(trim(file_get_contents($this->pidFilePath)));

        if($this->processControl->isRunning($pid, true)) {
            return $pid;
        }

        // Not found, remove the stale PID file
        unlink($this->pidFilePath);
    }
}
