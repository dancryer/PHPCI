#!/usr/bin/php
<?php

$dbHost = ask('Enter your MySQL host: ');
$dbName = ask('Enter the database name PHPCI should use: ');
$dbUser = ask('Enter your MySQL username: ');
$dbPass = ask('Enter your MySQL password: ');

$cmd	= 'mysql -u' . $dbUser . (!empty($dbPass) ? ' -p' . $dbPass : '') . ' -h' . $dbHost . ' -e "CREATE DATABASE IF NOT EXISTS ' . $dbName . '"';
shell_exec($cmd);

file_put_contents('./config.php', "<?php

define('PHPCI_DB_HOST', '{$dbHost}');

b8\Database::setDetails('{$dbName}', '{$dbUser}', '{$dbPass}');
b8\Database::setWriteServers(array('{$dbHost}'));
b8\Database::setReadServers(array('{$dbHost}'));

");

require_once('bootstrap.php');

$gen = new b8\Database\Generator(b8\Database::getConnection(), 'PHPCI', './PHPCI/Model/Base/');
$gen->generate();

print 'INSTALLING: Composer' . PHP_EOL;
file_put_contents('./composerinstaller.php', file_get_contents('https://getcomposer.org/installer'));
shell_exec('php ./composerinstaller.php');
unlink('./composerinstaller.php');

print 'RUNNING: Composer' . PHP_EOL;
shell_exec('./composer.phar install');


function ask($question)
{
	print $question . ' ';

	$rtn	= '';
	$fp		= fopen('php://stdin', 'r');
	$rtn = fgets($fp);
	fclose($fp);

	return trim($rtn);
}