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
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PHPCI\Command\RunCommand;

/**
* Daemon that loops and call the run-command.
* @author       Gabriel Baker <gabriel.baker@autonomicpilot.co.uk>
* @package      PHPCI
* @subpackage   Console
*/
class DaemoniseCommand extends Command
{
    /**
     * @var RunCommand
     */
    protected $runner;

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
     * @param \Monolog\Logger $logger
     * @param string $name
     */
    public function __construct(RunCommand $runner, Logger $logger)
    {
        parent::__construct();
        $this->runner = $runner;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this
            ->setName('phpci:daemonise')
            ->setDescription('Starts the daemon to run commands.')

            ->addOption(
                'pid-file', 'p', InputOption::VALUE_REQUIRED,
                'Path of the PID file',
                implode(DIRECTORY_SEPARATOR,
                    array(rtrim(PHPCI_DIR, '/'), 'daemon', 'daemon.pid'))
            );
    }

    /**
    * Loops through running.
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pidFilePath = $input->getOption('pid-file');
        $command = sprintf("echo %s > '%s'", getmypid(), $pidFilePath);
        exec($command);

        $this->output = $output;
        $this->run = true;
        $this->sleep = 0;
        $this->runner->setMaxBuilds(1);
        $this->runner->setDaemon(true);

        $emptyInput = new ArgvInput(array());

        while ($this->run) {
            $buildCount = 0;

            try {
                $buildCount = $this->runner->run($emptyInput, $output);
            } catch (\Exception $e) {
                $output->writeln('<error>Exception: ' . $e->getMessage() . '</error>');
                $output->writeln('<error>Line: ' . $e->getLine() . ' - File: ' . $e->getFile() . '</error>');
            }

            if (0 == $buildCount && $this->sleep < 15) {
                $this->sleep++;
            } elseif (1 < $this->sleep) {
                $this->sleep--;
            }
            echo '.'.(0 === $buildCount?'':'build');
            sleep($this->sleep);
        }
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
