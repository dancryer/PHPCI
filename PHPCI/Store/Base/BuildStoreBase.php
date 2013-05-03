<?php

/**
 * Build base store for table: build
 */

namespace PHPCI\Store\Base;
use b8\Store;

/**
 * Build Base Store
 */
class BuildStoreBase extends Store
{
	protected $_tableName   = 'build';
	protected $_modelName   = '\PHPCI\Model\Build';
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

		$stmt = \b8\Database::getConnection($useConnection)->prepare('SELECT * FROM build WHERE id = :id LIMIT 1');
		$stmt->bindValue(':id', $value);

		if($stmt->execute())
		{
			if($data = $stmt->fetch(\PDO::FETCH_ASSOC))
			{
				return new \PHPCI\Model\Build($data);
			}
		}

		return null;
	}

	public function getByProjectId($value, $limit = null, $useConnection = 'read')
	{
		if(is_null($value))
		{
			throw new \b8\Exception\HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
		}

		$add = '';

		if($limit)
		{
			$add .= ' LIMIT ' . $limit;
		}

		$stmt = \b8\Database::getConnection($useConnection)->prepare('SELECT COUNT(*) AS cnt FROM build WHERE project_id = :project_id' . $add);
		$stmt->bindValue(':project_id', $value);

		if($stmt->execute())
		{
			$res    = $stmt->fetch(\PDO::FETCH_ASSOC);
			$count  = (int)$res['cnt'];
		}
		else
		{
			$count = 0;
		}

		$stmt = \b8\Database::getConnection('read')->prepare('SELECT * FROM build WHERE project_id = :project_id' . $add);
		$stmt->bindValue(':project_id', $value);

		if($stmt->execute())
		{
			$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

			$rtn = array_map(function($item)
			{
				return new \PHPCI\Model\Build($item);
			}, $res);

			return array('items' => $rtn, 'count' => $count);
		}
		else
		{
			return array('items' => array(), 'count' => 0);
		}
	}

	public function getByStatus($value, $limit = null, $useConnection = 'read')
	{
		if(is_null($value))
		{
			throw new \b8\Exception\HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
		}

		$add = '';

		if($limit)
		{
			$add .= ' LIMIT ' . $limit;
		}

		$stmt = \b8\Database::getConnection($useConnection)->prepare('SELECT COUNT(*) AS cnt FROM build WHERE status = :status' . $add);
		$stmt->bindValue(':status', $value);

		if($stmt->execute())
		{
			$res    = $stmt->fetch(\PDO::FETCH_ASSOC);
			$count  = (int)$res['cnt'];
		}
		else
		{
			$count = 0;
		}

		$stmt = \b8\Database::getConnection('read')->prepare('SELECT * FROM build WHERE status = :status' . $add);
		$stmt->bindValue(':status', $value);

		if($stmt->execute())
		{
			$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

			$rtn = array_map(function($item)
			{
				return new \PHPCI\Model\Build($item);
			}, $res);

			return array('items' => $rtn, 'count' => $count);
		}
		else
		{
			return array('items' => array(), 'count' => 0);
		}
	}

}
