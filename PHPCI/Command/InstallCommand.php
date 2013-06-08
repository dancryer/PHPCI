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
use PHPCI\Builder;

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
        // Gather initial data from the user:
        $conf = array();
        $conf['b8']['database']['servers']['read']  = $this->ask('Enter your MySQL host: ');
        $conf['b8']['database']['servers']['write'] = $conf['b8']['database']['servers']['read'];
        $conf['b8']['database']['name']             = $this->ask('Enter the database name PHPCI should use: ');
        $conf['b8']['database']['username']         = $this->ask('Enter your MySQL username: ');
        $conf['b8']['database']['password']         = $this->ask('Enter your MySQL password: ', true);
        $conf['phpci']['url']                       = $this->ask('Your PHPCI URL (without trailing slash): ', false, array(FILTER_VALIDATE_URL,"/[^\/]$/i"));
        $conf['phpci']['github']['id']              = $this->ask('(Optional) Github Application ID: ', true);
        $conf['phpci']['github']['secret']          = $this->ask('(Optional) Github Application Secret: ', true);

        $conf['phpci']['email_settings']['smtp_address']  = $this->ask('(Optional) Smtp server address: ', true);
        $conf['phpci']['email_settings']['smtp_port']     = $this->ask('(Optional) Smtp port: ', true);
        $conf['phpci']['email_settings']['smtp_username'] = $this->ask('(Optional) Smtp Username: ', true);
        $conf['phpci']['email_settings']['smtp_password'] = $this->ask('(Optional) Smtp Password: ', true);
        $conf['phpci']['email_settings']['from_address']  = $this->ask('(Optional) Email address to send from: ', true);
        $conf['phpci']['email_settings']['default_mailto_address'] = $this->ask('(Optional) Default address to email notifications to: ', true);

        $dbUser = $conf['b8']['database']['username'];
        $dbPass = $conf['b8']['database']['password'];
        $dbHost = $conf['b8']['database']['servers']['write'];
        $dbName = $conf['b8']['database']['name'];

        // Create the database if it doesn't exist:
        $cmd    = 'mysql -u' . $dbUser . (!empty($dbPass) ? ' -p' . $dbPass : '') . ' -h' . $dbHost .
                    ' -e "CREATE DATABASE IF NOT EXISTS ' . $dbName . '"';

        shell_exec($cmd);

        $dumper = new \Symfony\Component\Yaml\Dumper();
        $yaml = $dumper->dump($conf);

        file_put_contents(PHPCI_DIR . 'PHPCI/config.yml', $yaml);

        require(PHPCI_DIR . 'bootstrap.php');

        // Update the database:
        $gen = new \b8\Database\Generator(\b8\Database::getConnection(), 'PHPCI', './PHPCI/Model/Base/');
        $gen->generate();

        // Try to create a user account:
        $adminEmail = $this->ask('Enter your email address (leave blank if updating): ', true, FILTER_VALIDATE_EMAIL);

        if (empty($adminEmail)) {
            return;
        }
        $adminPass = $this->ask('Enter your desired admin password: ');
        $adminName = $this->ask('Enter your name: ');

        try {
            $user = new \PHPCI\Model\User();
            $user->setEmail($adminEmail);
            $user->setName($adminName);
            $user->setIsAdmin(1);
            $user->setHash(password_hash($adminPass, PASSWORD_DEFAULT));

            $store = \b8\Store\Factory::getStore('User');
            $store->save($user);

            print 'User account created!' . PHP_EOL;
        } catch (\Exception $ex) {
            print 'There was a problem creating your account. :(' . PHP_EOL;
            print $ex->getMessage();
        }
    }

    protected function ask($question, $emptyOk = false, $validationFilter = null)
    {
        print $question . ' ';

        $rtn    = '';
        $stdin     = fopen('php://stdin', 'r');
        $rtn = fgets($stdin);
        fclose($stdin);

        $rtn = trim($rtn);

        if (!$emptyOk && empty($rtn)) {
            $rtn = $this->ask($question, $emptyOk, $validationFilter);
        } elseif ($validationFilter != null  && ! empty($rtn)) {
            if (! $this -> controlFormat($rtn, $validationFilter, $statusMessage)) {
                print $statusMessage;
                $rtn = $this->ask($question, $emptyOk, $validationFilter);
            }
        }

        return $rtn;
    }
    protected function controlFormat($valueToInspect,$filter,&$statusMessage)
    {
        $filters = !(is_array($filter))? array($filter) : $filter;
        $statusMessage = '';
        $status = true;
        $options = array();

        foreach ($filters as $filter) {
            if (! is_int($filter)) {
                $regexp = $filter;
                $filter = FILTER_VALIDATE_REGEXP;
                $options = array(
                    'options' => array(
                        'regexp' => $regexp,
                    )
                );
            }
            if (! filter_var($valueToInspect, $filter, $options)) {
                $status = false;

                switch ($filter)
                {
                    case FILTER_VALIDATE_URL :
                        $statusMessage = 'Incorrect url format.' . PHP_EOL;
                        break;
                    case FILTER_VALIDATE_EMAIL :
                        $statusMessage = 'Incorrect e-mail format.' . PHP_EOL;
                        break;
                    case FILTER_VALIDATE_REGEXP :
                        $statusMessage = 'Incorrect format.' . PHP_EOL;
                        break;
                }
            }
        }

        return $status;
    }
}
