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
use PHPCI\Helper\Lang;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate console command - Reads the database and generates models and stores.
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Console
 */
class UpdateCommand extends Command
{
    /**
     * @var \Monolog\Logger
     */
    protected $logger;

    public function __construct(Logger $logger, $name = null)
    {
        parent::__construct($name);
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this
            ->setName('phpci:update')
            ->setDescription(Lang::get('update_phpci'));
    }

    /**
     * Generates Model and Store classes by reading database meta data.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->verifyInstalled($output)) {
            return;
        }

        $output->write(Lang::get('updating_phpci'));

        shell_exec(PHPCI_DIR . 'vendor/bin/phinx migrate -c "' . PHPCI_DIR . 'phinx.php"');

        $output->writeln('<info>'.Lang::get('ok').'</info>');
    }

    protected function verifyInstalled(OutputInterface $output)
    {
        if (!file_exists(PHPCI_DIR . 'PHPCI/config.yml')) {
            $output->writeln('<error>'.Lang::get('not_installed').'</error>');
            $output->writeln('<error>'.Lang::get('install_instead').'</error>');
            return false;
        }

        $content = file_get_contents(PHPCI_DIR . 'PHPCI/config.yml');
        if (empty($content)) {
            $output->writeln('<error>'.Lang::get('not_installed').'</error>');
            $output->writeln('<error>'.Lang::get('install_instead').'</error>');
            return false;
        }

        return true;
    }
}
