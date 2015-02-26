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
use b8\Store\Factory;
use PHPCI\Helper\Lang;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
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
            ->addOption('url', null, InputOption::VALUE_OPTIONAL, Lang::get('installation_url'))
            ->addOption('db-host', null, InputOption::VALUE_OPTIONAL, Lang::get('db_host'))
            ->addOption('db-name', null, InputOption::VALUE_OPTIONAL, Lang::get('db_name'))
            ->addOption('db-user', null, InputOption::VALUE_OPTIONAL, Lang::get('db_user'))
            ->addOption('db-pass', null, InputOption::VALUE_OPTIONAL, Lang::get('db_pass'))
            ->addOption('admin-name', null, InputOption::VALUE_OPTIONAL, Lang::get('admin_name'))
            ->addOption('admin-pass', null, InputOption::VALUE_OPTIONAL, Lang::get('admin_pass'))
            ->addOption('admin-mail', null, InputOption::VALUE_OPTIONAL, Lang::get('admin_email'))
            ->addOption('config-path', null, InputOption::VALUE_OPTIONAL, Lang::get('config_path'), $defaultPath)
            ->setDescription(Lang::get('install_phpci'));
    }

    /**
     * Installs PHPCI - Can be run more than once as long as you ^C instead of entering an email address.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->configFilePath = $input->getOption('config-path');

        if (!$this->verifyNotInstalled($output)) {
            return;
        }

        $output->writeln('');
        $output->writeln('<info>******************</info>');
        $output->writeln('<info> '.Lang::get('welcome_to_phpci').'</info>');
        $output->writeln('<info>******************</info>');
        $output->writeln('');

        $this->checkRequirements($output);

        $output->writeln(Lang::get('please_answer'));
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
        $admin = $this->getAdminInformation($input, $output);
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
        if (!(version_compare(PHP_VERSION, '5.3.8') >= 0)) {
            $output->writeln('');
            $output->writeln('<error>'.Lang::get('phpci_php_req').'</error>');
            $errors = true;
        }

        // Check required extensions are present:
        $requiredExtensions = array('PDO', 'pdo_mysql');

        foreach ($requiredExtensions as $extension) {
            if (!extension_loaded($extension)) {
                $output->writeln('');
                $output->writeln('<error>'.Lang::get('extension_required', $extension).'</error>');
                $errors = true;
            }
        }

        // Check required functions are callable:
        $requiredFunctions = array('exec', 'shell_exec');

        foreach ($requiredFunctions as $function) {
            if (!function_exists($function)) {
                $output->writeln('');
                $output->writeln('<error>'.Lang::get('function_required', $function).'</error>');
                $errors = true;
            }
        }

        if (!function_exists('password_hash')) {
            $output->writeln('');
            $output->writeln('<error>'.Lang::get('function_required', $function).'</error>');
            $errors = true;
        }

        if ($errors) {
            throw new Exception(Lang::get('requirements_not_met'));
        }

        $output->writeln(' <info>'.Lang::get('ok').'</info>');
        $output->writeln('');
    }

    /**
     * Load information for admin user form CLI options or ask info to user.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     */
    protected function getAdminInformation(InputInterface $input, OutputInterface $output)
    {
        $admin = array();

        /**
         * @var \Symfony\Component\Console\Helper\DialogHelper
         */
        $dialog = $this->getHelperSet()->get('dialog');

        // Function to validate mail address.
        $mailValidator = function ($answer) {
            if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException(Lang::get('must_be_valid_email'));
            }

            return $answer;
        };

        if ($adminEmail = $input->getOption('admin-mail')) {
            $adminEmail = $mailValidator($adminEmail);
        } else {
            $adminEmail = $dialog->askAndValidate($output, Lang::get('enter_email'), $mailValidator, false);
        }
        if (!$adminName = $input->getOption('admin-name')) {
            $adminName = $dialog->ask($output, Lang::get('enter_name'));
        }
        if (!$adminPass = $input->getOption('admin-pass')) {
            $adminPass = $dialog->askHiddenResponse($output, Lang::get('enter_password'));
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
                throw new Exception(Lang::get('must_be_valid_url'));
            }

            return rtrim($answer, '/');
        };

        if ($url = $input->getOption('url')) {
            $url = $urlValidator($url);
        } else {
            $url = $dialog->askAndValidate($output, Lang::get('enter_phpci_url'), $urlValidator, false);
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
            $dbHost = $dialog->ask($output, Lang::get('enter_db_host'), 'localhost');
        }

        if (!$dbName = $input->getOption('db-name')) {
            $dbName = $dialog->ask($output, Lang::get('enter_db_name'), 'phpci');
        }

        if (!$dbUser = $input->getOption('db-user')) {
            $dbUser = $dialog->ask($output, Lang::get('enter_db_user'), 'phpci');
        }

        if (!$dbPass = $input->getOption('db-pass')) {
            $dbPass = $dialog->askHiddenResponse($output, Lang::get('enter_db_pass'));
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

            unset($pdo);

            return true;

        } catch (Exception $ex) {
            $output->writeln('<error>'.Lang::get('could_not_connect').'</error>');
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
        $yaml = $dumper->dump($config, 4);

        file_put_contents($this->configFilePath, $yaml);
    }

    protected function setupDatabase(OutputInterface $output)
    {
        $output->write(Lang::get('setting_up_db'));

        shell_exec(PHPCI_DIR . 'vendor/bin/phinx migrate -c "' . PHPCI_DIR . 'phinx.php"');

        $output->writeln('<info>'.Lang::get('ok').'</info>');
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

            $output->writeln('<info>'.Lang::get('user_created').'</info>');
        } catch (\Exception $ex) {
            $output->writeln('<error>'.Lang::get('failed_to_create').'</error>');
            $output->writeln('<error>' . $ex->getMessage() . '</error>');
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
     * @return bool
     */
    protected function verifyNotInstalled(OutputInterface $output)
    {
        if (file_exists($this->configFilePath)) {
            $content = file_get_contents($this->configFilePath);

            if (!empty($content)) {
                $output->writeln('<error>'.Lang::get('config_exists').'</error>');
                $output->writeln('<error>'.Lang::get('update_instead').'</error>');
                return false;
            }
        }

        return true;
    }
}
