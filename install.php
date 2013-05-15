<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

$dbHost = ask('Enter your MySQL host: ');
$dbName = ask('Enter the database name PHPCI should use: ');
$dbUser = ask('Enter your MySQL username: ');
$dbPass = ask('Enter your MySQL password: ', true);
$ciUrl = ask('Your PHPCI URL (without trailing slash): ', true);
$ghId = ask('(Optional) Github Application ID: ', true);
$ghSecret = ask('(Optional) Github Application Secret: ', true);

$cmd	= 'mysql -u' . $dbUser . (!empty($dbPass) ? ' -p' . $dbPass : '') . ' -h' . $dbHost . ' -e "CREATE DATABASE IF NOT EXISTS ' . $dbName . '"';
shell_exec($cmd);

$str = "<?php

define('PHPCI_DB_HOST', '{$dbHost}');

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


file_put_contents('./config.php', $str);

if(!file_exists('./composer.phar'))
{
	print 'INSTALLING: Composer' . PHP_EOL;
	file_put_contents('./composerinstaller.php', file_get_contents('https://getcomposer.org/installer'));
	shell_exec('php composerinstaller.php');
	unlink('./composerinstaller.php');
}

print 'RUNNING: Composer' . PHP_EOL;
shell_exec('php composer.phar install');


require_once('bootstrap.php');

$gen = new b8\Database\Generator(b8\Database::getConnection(), 'PHPCI', './PHPCI/Model/Base/');
$gen->generate();

$adminEmail = ask('Enter your email address: ');
$adminPass = ask('Enter your desired admin password: ');
$adminName = ask('Enter your name: ');

try
{
	$user = new PHPCI\Model\User();
	$user->setEmail($adminEmail);
	$user->setName($adminName);
	$user->setIsAdmin(1);
	$user->setHash(password_hash($adminPass, PASSWORD_DEFAULT));

	$store = b8\Store\Factory::getStore('User');
	$store->save($user);

	print 'User account created!' . PHP_EOL;
}
catch(Exception $ex)
{
	print 'There was a problem creating your account. :(' . PHP_EOL;
	print $ex->getMessage();
}


function ask($question, $emptyOk = false)
{
	print $question . ' ';

	$rtn	= '';
	$fp		= fopen('php://stdin', 'r');
	$rtn = fgets($fp);
	fclose($fp);

	$rtn = trim($rtn);

	if(!$emptyOk && empty($rtn))
	{
		$rtn = ask($question, $emptyOk);
	}

	return $rtn;
}