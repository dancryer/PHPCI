<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use b8\Database;
use b8\Database\CodeGenerator;

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
        $gen = new CodeGenerator(
            Database::getConnection(),
            array('default' => 'Kiboko\\Component\\ContinuousIntegration'),
            array('default' => KIBOKO_CI_APP_DIR),
            false
        );

        $gen->generateModels();
        $gen->generateStores();
    }
}
