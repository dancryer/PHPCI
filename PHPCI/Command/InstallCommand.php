<?php

namespace PHPCI\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use b8\Store\Factory;
use PHPCI\Builder;

class InstallCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('phpci:install')
            ->setDescription('Install PHPCI.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dbHost = $this->ask('Enter your MySQL host: ');
        $dbName = $this->ask('Enter the database name PHPCI should use: ');
        $dbUser = $this->ask('Enter your MySQL username: ');
        $dbPass = $this->ask('Enter your MySQL password: ', true);
        $ciUrl = $this->ask('Your PHPCI URL (without trailing slash): ', true);
        $ghId = $this->ask('(Optional) Github Application ID: ', true);
        $ghSecret = $this->ask('(Optional) Github Application Secret: ', true);

        $cmd    = 'mysql -u' . $dbUser . (!empty($dbPass) ? ' -p' . $dbPass : '') . ' -h' . $dbHost . ' -e "CREATE DATABASE IF NOT EXISTS ' . $dbName . '"';
        shell_exec($cmd);

        $str = "<?php

if(!defined('PHPCI_DB_HOST')) {
    define('PHPCI_DB_HOST', '{$dbHost}');
}

b8\Database::setDetails('{$dbName}', '{$dbUser}', '{$dbPass}');
b8\Database::setWriteServers(array('{$dbHost}'));
b8\Database::setReadServers(array('{$dbHost}'));

\$registry = b8\Registry::getInstance();
\$registry->set('install_url', '{$ciUrl}');
";

        if(!empty($ghId) && !empty($ghSecret))
        {
            $str .= PHP_EOL . "\$registry->set('github_app', array('id' => '{$ghId}', 'secret' => '{$ghSecret}'));" . PHP_EOL;
        }


        file_put_contents(PHPCI_DIR . 'config.php', $str);

        require(PHPCI_DIR . 'bootstrap.php');

        $gen = new \b8\Database\Generator(\b8\Database::getConnection(), 'PHPCI', './PHPCI/Model/Base/');
        $gen->generate();

        $adminEmail = $this->ask('Enter your email address: ');
        $adminPass = $this->ask('Enter your desired admin password: ');
        $adminName = $this->ask('Enter your name: ');

        try
        {
            $user = new \PHPCI\Model\User();
            $user->setEmail($adminEmail);
            $user->setName($adminName);
            $user->setIsAdmin(1);
            $user->setHash(password_hash($adminPass, PASSWORD_DEFAULT));

            $store = \b8\Store\Factory::getStore('User');
            $store->save($user);

            print 'User account created!' . PHP_EOL;
        }
        catch(\Exception $ex)
        {
            print 'There was a problem creating your account. :(' . PHP_EOL;
            print $ex->getMessage();
        }
    }

    function ask($question, $emptyOk = false)
    {
        print $question . ' ';

        $rtn    = '';
        $fp     = fopen('php://stdin', 'r');
        $rtn = fgets($fp);
        fclose($fp);

        $rtn = trim($rtn);

        if(!$emptyOk && empty($rtn))
        {
            $rtn = $this->ask($question, $emptyOk);
        }

        return $rtn;
    }
}