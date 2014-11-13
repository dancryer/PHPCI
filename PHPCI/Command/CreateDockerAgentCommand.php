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
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Console
 */
class CreateDockerAgentCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('phpci:create-agent')
            ->setDescription('Create a build agent using a specified Dockerfile.')
            ->addArgument('name', InputArgument::REQUIRED)
            ->addArgument('dockerfile', InputArgument::REQUIRED);
    }

    /**
     * Generates Model and Store classes by reading database meta data.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dockerFile = $input->getArgument('dockerfile');

        if (!file_exists($dockerFile)) {
            throw new \Exception('Dockerfile does not exist: ' . $dockerFile);
        }

        $contents = file_get_contents($dockerFile);
        file_put_contents(PHPCI_DIR . 'Dockerfile', $contents);

        passthru('cd '.PHPCI_DIR.' && docker build -t ' . $input->getArgument('name') . ' .');
        @unlink(PHPCI_DIR . 'Dockerfile');
    }
}
