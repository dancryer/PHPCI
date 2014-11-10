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

use b8\Config;
use b8\Database;
use b8\Store\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use PHPCI\Service\UserService;

/**
 * Install console command - Installs PHPCI.
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Console
 */
class InstallCommand extends Command
{
    protected $configFilePath;

    protected function configure()
    {
        $defaultPath = PHPCI_DIR . 'PHPCI/config.yml';
        
        $this
            ->setName('phpci:install')
            ->addOption('url', null, InputOption::VALUE_OPTIONAL, 'PHPCI Installation URL')
            ->addOption('db-host', null, InputOption::VALUE_OPTIONAL, 'Database hostname')
            ->addOption('db-name', null, InputOption::VALUE_OPTIONAL, 'Database name')
            ->addOption('db-user', null, InputOption::VALUE_OPTIONAL, 'Database username')
            ->addOption('db-pass', null, InputOption::VALUE_OPTIONAL, 'Database password')
            ->addOption('admin-name', null, InputOption::VALUE_OPTIONAL, 'Admin username')
            ->addOption('admin-pass', null, InputOption::VALUE_OPTIONAL, 'Admin password')
            ->addOption('admin-mail', null, InputOption::VALUE_OPTIONAL, 'Admin e-mail')
            ->addOption('config-path', null, InputOption::VALUE_OPTIONAL, 'Config file path', $defaultPath)
            ->setDescription('Install PHPCI.');
    }

    /**
     * Installs PHPCI - Can be run more than once as long as you ^C instead of entering an email address.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->configFilePath = $input->getOption('config-path');

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

        // ----
        // Get MySQL connection information and verify that it works:
        // ----
        $connectionVerified = false;

        while (!$connectionVerified) {
            $db = $this->getDatabaseInformation($input, $output);

            $connectionVerified = $this->verifyDatabaseDetails($db, $output);
        }

        $output->writeln('');

        $conf = array();
        $conf['b8']['database'] = $db;

        // ----
        // Get basic installation details (URL, etc)
        // ----
        $conf['phpci'] = $this->getPhpciConfigInformation($input, $output);

        $this->writeConfigFile($conf);
        $this->setupDatabase($output);
        $admin = $this->getAdminInforamtion($input, $output);
        $this->createAdminUser($admin, $output);
    }

    /**
     * Check PHP version, required modules and for disabled functions.
     *
     * @param  OutputInterface $output
     * @throws \Exception
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
     * Load information for admin user form CLI options or ask info to user.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     */
    protected function getAdminInforamtion(InputInterface $input, OutputInterface $output)
    {
        $admin = array();

        /**
         * @var \Symfony\Component\Console\Helper\DialogHelper
         */
        $dialog = $this->getHelperSet()->get('dialog');

        // Function to validate mail address.
        $mailValidator =function ($answer) {
            if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Must be a valid email address.');
            }

            return $answer;
        };

        if ($adminEmail = $input->getOption('admin-mail')) {
            $adminEmail = $mailValidator($adminEmail);
        } else {
            $adminEmail = $dialog->askAndValidate($output, 'Your email address: ', $mailValidator, false);
        }
        if (!$adminName = $input->getOption('admin-name')) {
            $adminName = $dialog->ask($output, 'Enter your name: ');
        }
        if (!$adminPass = $input->getOption('admin-pass')) {
            $adminPass = $dialog->askHiddenResponse($output, 'Enter your desired admin password: ');
        }

        $admin['mail'] = $adminEmail;
        $admin['name'] = $adminName;
        $admin['pass'] = $adminPass;

        return $admin;
    }

    /**
     * Load configuration for PHPCI form CLI options or ask info to user.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     */
    protected function getPhpciConfigInformation(InputInterface $input, OutputInterface $output)
    {
        $phpci = array();

        /**
         * @var \Symfony\Component\Console\Helper\DialogHelper
         */
        $dialog = $this->getHelperSet()->get('dialog');

        // FUnction do validate URL.
        $urlValidator = function ($answer) {
            if (!filter_var($answer, FILTER_VALIDATE_URL)) {
                throw new Exception('Must be a valid URL');
            }

            return rtrim($answer, '/');
        };

        if ($url = $input->getOption('url')) {
            $url = $urlValidator($url);
        } else {
            $url = $dialog->askAndValidate($output, 'Your PHPCI URL ("http://phpci.local" for example): ', $urlValidator, false);
        }

        $phpci['url'] = $url;

        return $phpci;
    }

    /**
     * Load configuration for DB form CLI options or ask info to user.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     */
    protected function getDatabaseInformation(InputInterface $input, OutputInterface $output)
    {
        $db = array();

        /**
         * @var \Symfony\Component\Console\Helper\DialogHelper
         */
        $dialog = $this->getHelperSet()->get('dialog');

        if (!$dbHost = $input->getOption('db-host')) {
            $dbHost = $dialog->ask($output, 'Please enter your MySQL host [localhost]: ', 'localhost');
        }

        if (!$dbName = $input->getOption('db-name')) {
            $dbName = $dialog->ask($output, 'Please enter your database name [phpci]: ', 'phpci');
        }

        if (!$dbUser = $input->getOption('db-user')) {
            $dbUser = $dialog->ask($output, 'Please enter your database username [phpci]: ', 'phpci');
        }

        if (!$dbPass = $input->getOption('db-pass')) {
            $dbPass = $dialog->askHiddenResponse($output, 'Please enter your database password: ');
        }

        $db['servers']['read'] = $dbHost;
        $db['servers']['write'] = $dbHost;
        $db['name'] = $dbName;
        $db['username'] = $dbUser;
        $db['password'] = $dbPass;

        return $db;
    }

    /**
     * Try and connect to MySQL using the details provided.
     * @param  array           $db
     * @param  OutputInterface $output
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

        file_put_contents($this->configFilePath, $yaml);
    }

    protected function setupDatabase(OutputInterface $output)
    {
        $output->write('Setting up your database... ');

        shell_exec(PHPCI_DIR . 'vendor/bin/phinx migrate -c "' . PHPCI_DIR . 'phinx.php"');

        $output->writeln('<info>OK</info>');
    }

    /**
     * Create admin user using information loaded before.
     *
     * @param array $admin
     * @param OutputInterface $output
     */
    protected function createAdminUser($admin, $output)
    {
        try {
            $this->reloadConfig();

            $userStore = Factory::getStore('User');
            $userService = new UserService($userStore);
            $userService->createUser($admin['name'], $admin['mail'], $admin['pass'], 1);

            $output->writeln('<info>User account created!</info>');
        } catch (\Exception $ex) {
            $output->writeln('<error>PHPCI failed to create your admin account.</error>');
            $output->writeln('<error>' . $ex->getMessage() . '</error>');
            die;
        }
    }

    protected function reloadConfig()
    {
        $config = Config::getInstance();

        if (file_exists($this->configFilePath)) {
            $config->loadYaml($this->configFilePath);
        }
    }

    /**
     * @param OutputInterface $output
     */
    protected function verifyNotInstalled(OutputInterface $output)
    {
        if (file_exists($this->configFilePath)) {
            $content = file_get_contents($this->configFilePath);

            if (!empty($content)) {
                $output->writeln('<error>The PHPCI config file exists and is not empty.</error>');
                $output->writeln('<error>If you were trying to update PHPCI, please use phpci:update instead.</error>');
                die;
            }
        }
    }
}
