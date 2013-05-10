<?php

/**
 * User base store for table: user
 */

namespace PHPCI\Store\Base;
use b8\Store;

/**
 * User Base Store
 */
class UserStoreBase extends Store
{
	protected $_tableName   = 'user';
	protected $_modelName   = '\PHPCI\Model\User';
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

		$stmt = \b8\Database::getConnection($useConnection)->prepare('SELECT * FROM user WHERE id = :id LIMIT 1');
		$stmt->bindValue(':id', $value);

		if($stmt->execute())
		{
			if($data = $stmt->fetch(\PDO::FETCH_ASSOC))
			{
				return new \PHPCI\Model\User($data);
			}
		}

		return null;
	}

	public function getByEmail($value, $useConnection = 'read')
	{
		if(is_null($value))
		{
			throw new \b8\Exception\HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
		}

		$stmt = \b8\Database::getConnection($useConnection)->prepare('SELECT * FROM user WHERE email = :email LIMIT 1');
		$stmt->bindValue(':email', $value);

		if($stmt->execute())
		{
			if($data = $stmt->fetch(\PDO::FETCH_ASSOC))
			{
				return new \PHPCI\Model\User($data);
			}
		}

		return null;
	}

}
