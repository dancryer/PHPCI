<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Command;

use Exception;
use PDO;

use b8\Database;
use b8\Store\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use PHPCI\Model\User;


/**
 * Install console command - Installs PHPCI.
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Console
 */
class InstallCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('phpci:install')
            ->setDescription('Install PHPCI.');
    }

    /**
     * Installs PHPCI - Can be run more than once as long as you ^C instead of entering an email address.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->verifyNotInstalled($output);

        $output->writeln('');
        $output->writeln('<info>******************</info>');
        $output->writeln('<info> Welcome to PHPCI</info>');
        $output->writeln('<info>******************</info>');
        $output->writeln('');

        $this->checkRequirements($output);

        $output->writeln('Please answer the following questions:');
        $output->writeln('-------------------------------------');
        $output->writeln('');


        /**
         * @var \Symfony\Component\Console\Helper\DialogHelper
         */
        $dialog = $this->getHelperSet()->get('dialog');

        // ----
        // Get MySQL connection information and verify that it works:
        // ----
        $connectionVerified = false;

        while (!$connectionVerified) {
            $db = array();
            $db['servers']['read'] = $dialog->ask($output, 'Please enter your MySQL host [localhost]: ', 'localhost');
            $db['servers']['write'] = $db['servers']['read'];
            $db['name'] = $dialog->ask($output, 'Please enter your database name [phpci]: ', 'phpci');
            $db['username'] = $dialog->ask($output, 'Please enter your database username [phpci]: ', 'phpci');
            $db['password'] = $dialog->askHiddenResponse($output, 'Please enter your database password: ');

            $connectionVerified = $this->verifyDatabaseDetails($db, $output);
        }

        $output->writeln('');

        // ----
        // Get basic installation details (URL, etc)
        // ----

        $conf = array();
        $conf['b8']['database'] = $db;
        $conf['phpci']['url'] = $dialog->askAndValidate(
            $output,
            'Your PHPCI URL (without trailing slash): ',
            function ($answer) {
                if (!filter_var($answer, FILTER_VALIDATE_URL)) {
                    throw new Exception('Must be a valid URL');
                }

                return $answer;
            },
            false
        );

        $this->writeConfigFile($conf);
        $this->setupDatabase($output);
        $this->createAdminUser($output, $dialog);
    }

    /**
     * Check PHP version, required modules and for disabled functions.
     * @param OutputInterface $output
     */
    protected function checkRequirements(OutputInterface $output)
    {
        $output->write('Checking requirements...');
        $errors = false;

        // Check PHP version:
        if (!(version_compare(PHP_VERSION, '5.3.3') >= 0)) {
            $output->writeln('');
            $output->writeln('<error>PHPCI requires at least PHP 5.3.3 to function.</error>');
            $errors = true;
        }

        // Check required extensions are present:
        $requiredExtensions = array('PDO', 'pdo_mysql', 'mcrypt');

        foreach ($requiredExtensions as $extension) {
            if (!extension_loaded($extension)) {
                $output->writeln('');
                $output->writeln('<error>'.$extension.' extension must be installed.</error>');
                $errors = true;
            }
        }

        // Check required functions are callable:
        $requiredFunctions = array('exec', 'shell_exec');

        foreach ($requiredFunctions as $function) {
            if (!function_exists($function)) {
                $output->writeln('');
                $output->writeln('<error>PHPCI needs to be able to call the '.$function.'() function. Is it disabled in php.ini?</error>');
                $errors = true;
            }
        }

        if (!function_exists('password_hash')) {
            $output->writeln('');
            $output->writeln('<error>PHPCI requires the password_hash() function available in PHP 5.4, or the password_compat library by ircmaxell.</error>');
            $errors = true;
        }

        if ($errors) {
            throw new Exception('PHPCI cannot be installed, as not all requirements are met. Please review the errors above before continuing.');
        }

        $output->writeln(' <info>OK</info>');
        $output->writeln('');
    }

    /**
     * Try and connect to MySQL using the details provided.
     * @param array $db
     * @param OutputInterface $output
     * @return bool
     */
    protected function verifyDatabaseDetails(array $db, OutputInterface $output)
    {
        try {
            $pdo = new PDO(
                'mysql:host='.$db['servers']['write'].';dbname='.$db['name'],
                $db['username'],
                $db['password'],
                array(
                    \PDO::ATTR_PERSISTENT => false,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_TIMEOUT => 2,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
                )
            );

            return true;

        } catch (Exception $ex) {
            $output->writeln('<error>PHPCI could not connect to MySQL with the details provided. Please try again.</error>');
            $output->writeln('<error>' . $ex->getMessage() . '</error>');
        }

        return false;
    }

    /**
     * Write the PHPCI config.yml file.
     * @param array $config
     */
    protected function writeConfigFile(array $config)
    {
        $dumper = new \Symfony\Component\Yaml\Dumper();
        $yaml = $dumper->dump($config, 2);

        file_put_contents(PHPCI_DIR . 'PHPCI/config.yml', $yaml);
    }

    protected function setupDatabase(OutputInterface $output)
    {
        $output->write('Setting up your database... ');

        shell_exec(PHPCI_DIR . 'vendor/bin/phinx migrate -c "' . PHPCI_DIR . 'phinx.php"');

        $output->writeln('<info>OK</info>');
    }

    protected function createAdminUser(OutputInterface $output, DialogHelper $dialog)
    {
        // Try to create a user account:
        $adminEmail = $dialog->askAndValidate(
            $output,
            'Your email address: ',
            function ($answer) {
                if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Must be a valid email address.');
                }

                return $answer;
            },
            false
        );

        $adminPass = $dialog->askHiddenResponse($output, 'Enter your desired admin password: ');
        $adminName = $dialog->ask($output, 'Enter your name: ');

        try {
            $user = new User();
            $user->setEmail($adminEmail);
            $user->setName($adminName);
            $user->setIsAdmin(1);
            $user->setHash(password_hash($adminPass, PASSWORD_DEFAULT));

            $store = Factory::getStore('User');
            $store->save($user);

            $output->writeln('<info>User account created!</info>');
        } catch (\Exception $ex) {
            $output->writeln('<error>PHPCI failed to create your admin account.</error>');
            $output->writeln('<error>' . $ex->getMessage() . '</error>');
            die;
        }
    }

    protected function verifyNotInstalled(OutputInterface $output)
    {
        if (file_exists(PHPCI_DIR . 'PHPCI/config.yml')) {
            $content = file_get_contents(PHPCI_DIR . 'PHPCI/config.yml');

            if (!empty($content)) {
                $output->writeln('<error>PHPCI/config.yml exists and is not empty.</error>');
                $output->writeln('<error>If you were trying to update PHPCI, please use phpci:update instead.</error>');
                die;
            }
        }
    }
}
