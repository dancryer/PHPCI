<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
* Daemon that loops and call the run-command.
* @author       Gabriel Baker <gabriel.baker@autonomicpilot.co.uk>
* @package      PHPCI
* @subpackage   Console
*/
class DaemoniseCommand extends RunCommand
{
    protected function configure()
    {
        $this
            ->setName('phpci:daemonise')
            ->setDescription('Starts the daemon to run commands.')
            ->addOption(
                'interval',
                'i',
                InputOption::VALUE_REQUIRED,
                'Maximum interval between scanning of pending builds.',
                15
            );
    }

    /**
    * Loops through running.
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        file_put_contents(PHPCI_DIR . DIRECTORY_SEPARATOR . "daemon" . DIRECTORY_SEPARATOR . "daemon.pid", getmypid());

        $maxSleep = (int)$input->getOption('interval');

        $run   = true;
        $sleep = 0;

        while ($run) {
            $build = $this->findNextBuild();

            if ($build) {
                $this->runBuild($build);
                $sleep = 0;
            } else {
                if ($sleep < $maxSleep) {
                    $sleep++;
                }
                sleep($sleep);
            }
        }
    }
}
