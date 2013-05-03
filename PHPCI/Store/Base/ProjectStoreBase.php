<?php

/**
 * Project base store for table: project
 */

namespace PHPCI\Store\Base;
use b8\Store;

/**
 * Project Base Store
 */
class ProjectStoreBase extends Store
{
	protected $_tableName   = 'project';
	protected $_modelName   = '\PHPCI\Model\Project';
	protected $_primaryKey  = 'id';

	public function getByPrimaryKey($value, $useConnection = 'read')
	{
		return $this->getById($value, $useConnection);
	}



	public function getById($value, $useConnection = 'read')
	{
		if(is_null($value))
		{
			throw new \b8\Exception\HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
		}

		$stmt = \b8\Database::getConnection($useConnection)->prepare('SELECT * FROM project WHERE id = :id LIMIT 1');
		$stmt->bindValue(':id', $value);

		if($stmt->execute())
		{
			if($data = $stmt->fetch(\PDO::FETCH_ASSOC))
			{
				return new \PHPCI\Model\Project($data);
			}
		}

		return null;
	}

}
