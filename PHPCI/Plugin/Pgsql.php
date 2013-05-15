<?php

namespace PHPCI\Plugin;
use PDO;

class Pgsql implements \PHPCI\Plugin
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

		$buildSettings = $phpci->getConfig('build_settings');

		if(isset($buildSettings['pgsql'])) {
			$sql		= $buildSettings['pgsql'];
			$this->host = $sql['host'];
			$this->user = $sql['user'];
			$this->pass = $sql['pass'];
		}
	}

	public function execute()
	{
		try {
			$pdo = new PDO('pgsql:host=' . $this->host, $this->user, $this->pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

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