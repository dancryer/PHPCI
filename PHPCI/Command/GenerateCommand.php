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

/**
* Generate console command - Reads the database and generates models and stores.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Console
*/
class GenerateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('phpci:generate')
            ->setDescription('Generate models and stores from the database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $gen = new \b8\Database\CodeGenerator(\b8\Database::getConnection(), 'PHPCI', PHPCI_DIR . '/PHPCI/');
        $gen->generateModels();
        $gen->generateStores();
    }
}