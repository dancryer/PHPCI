<?php
/**
* PHPCI - Continuous Integration for PHP
* nohup PHPCI_DIR/console phpci:start-daemon > /dev/null 2>&1 &
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Command;

use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
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
class DaemoniseCommand extends Command
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
            ->setName('phpci:daemonise')
            ->setDescription('Starts the daemon to run commands.');
    }

    /**
    * Loops through running.
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cmd = "echo %s > '%s/daemon/daemon.pid'";
        $command = sprintf($cmd, getmypid(), PHPCI_DIR);
        exec($command);

        $this->output = $output;
        $this->run   = true;
        $this->sleep = 0;
        $runner      = new RunCommand($this->logger);

        $emptyInput = new ArgvInput(array());

        while ($this->run) {

            $buildCount = 0;

            try {
                $buildCount = $runner->run($emptyInput, $output);
            } catch (\Exception $e) {
                var_dump($e);
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
    * @see \PHPCI\Builder::log()
    */
    public function logCallback($log)
    {
        $this->output->writeln($log);
    }
}
