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

		$db	= \b8\Database::getConnection('write')->getDetails();

		foreach($this->queries as $query)
		{
			$rtn = !$this->phpci->executeCommand('mysql -h'.PHPCI_DB_HOST.' -u'.$db['user'].(!empty($db['pass']) ? ' -p' . $db['pass'] : '').' -e "'.$query.'"') ? false : $rtn;
		}

		return $rtn;
	}
}