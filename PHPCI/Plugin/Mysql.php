<?php

namespace PHPCI\Plugin;
use PDO;

class Mysql implements \PHPCI\Plugin
{
	protected $phpci;
	protected $queries = array();

	protected $host;
	protected $user;
	protected $pass;

	public function __construct(\PHPCI\Builder $phpci, array $options = array())
	{
		$this->phpci		= $phpci;
		$this->queries		= $options;

		$db			= \b8\Database::getConnection('write')->getDetails();
		$this->host = PHPCI_DB_HOST;
		$this->user = $db['user'];
		$this->pass = $db['pass'];

		$buildSettings = $phpci->getConfig('build_settings');
		if(isset($buildSettings['mysql'])) {
			$sql	= $buildSettings['mysql'];

			$this->host = !empty($sql['host']) ? $sql['host'] : $this->host;
			$this->user = !empty($sql['user']) ? $sql['user'] : $this->user;
			$this->pass = array_key_exists('pass', $sql) ? $sql['pass'] : $this->pass;
		}
	}

	public function execute()
	{
		try {
			$pdo = new PDO('mysql:host=' . $this->host, $this->user, $this->pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

			foreach($this->queries as $query) {
				$pdo->query($query);
			}
		}
		catch(\Exception $ex) {
			$this->phpci->logFailure($ex->getMessage());
			return false;
		}

		return true;
	}
}