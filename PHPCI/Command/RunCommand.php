<?php
/**
* PHPCI - Continuous Integration for PHP
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
use PHPCI\Builder,
    PHPCI\BuildFactory;

class RunCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('phpci:run-builds')
            ->setDescription('Run all pending PHPCI builds.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $store  = Factory::getStore('Build');
        $result = $store->getByStatus(0);

        foreach($result['items'] as $build)
        {
            $build = BuildFactory::getBuild($build);
            
            if ($input->getOption('verbose')) {
                $builder = new Builder($build, array($this, 'logCallback'));
            }
            else {
                $builder = new Builder($build);
            }
            
            $builder->execute();
        }
    }

    public function logCallback($log)
    {
        $this->output->writeln($log);
    }
}