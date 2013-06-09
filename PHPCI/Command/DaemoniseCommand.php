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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use b8\Store\Factory;
use PHPCI\Builder;
use PHPCI\BuildFactory;

/**
* Daemon that loops and call the run-command.
* @author       Gabriel Baker <gabriel.baker@autonomicpilot.co.uk>
* @package      PHPCI
* @subpackage   Console
*/
class DaemoniseCommand extends Command
{
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

        $this->run   = true;
        $this->sleep = 0;
        $runner      = new RunCommand;

        while ($this->run) {

            try {
                $buildCount = $runner->execute($input, $output);
            } catch (\Exception $e) {
                var_dump($e);
            }

            if (0 == $buildCount && $this->sleep < 15) {
                $this->sleep++;
            } else if (1 < $this->sleep) {
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
