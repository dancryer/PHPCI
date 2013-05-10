<?php

namespace PHPCI\Plugin;

class Mysql implements \PHPCI\Plugin
{
	protected $phpci;
	protected $queries = array();

	public function __construct(\PHPCI\Builder $phpci, array $options = array())
	{
		$this->phpci		= $phpci;
		$this->queries		= $options;
	}

	public function execute()
	{
		$rtn = true;

		foreach($this->queries as $query)
		{
			$rtn = !$this->phpci->executeCommand('mysql -uroot -e "'.$query.'"') ? false : $rtn;
		}

		return $rtn;
	}
}