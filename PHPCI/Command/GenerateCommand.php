<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Command;

use Block8\Database\Connection;
use Block8\Database\Mapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PHPCI\Database\CodeGenerator;

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

    /**
    * Generates Model and Store classes by reading database meta data.
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = Connection::get();
        $mapper = new Mapper($connection);

        $gen = new CodeGenerator($mapper, ['default' => 'PHPCI'], ['default' => PHPCI_DIR . 'PHPCI/']);
        $gen->generateModels();
        $gen->generateStores();
    }
}
