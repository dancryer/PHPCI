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
    protected $tableName   = 'build';
    protected $modelName   = '\PHPCI\Model\Build';
    protected $primaryKey  = 'id';

    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }



    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new \b8\Exception\HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = 'SELECT * FROM build WHERE id = :id LIMIT 1';
        $stmt = \b8\Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':id', $value);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new \PHPCI\Model\Build($data);
            }
        }

        return null;
    }

    public function getByProjectId($value, $limit = null, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new \b8\Exception\HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $add = '';

        if ($limit) {
            $add .= ' LIMIT ' . $limit;
        }

        $query = 'SELECT COUNT(*) AS cnt FROM build WHERE project_id = :project_id' . $add;
        $stmt = \b8\Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':project_id', $value);

        if ($stmt->execute()) {
            $res    = $stmt->fetch(\PDO::FETCH_ASSOC);
            $count  = (int)$res['cnt'];
        } else {
            $count = 0;
        }

        $query = 'SELECT * FROM build WHERE project_id = :project_id' . $add;
        $stmt = \b8\Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':project_id', $value);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new \PHPCI\Model\Build($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }

    public function getByStatus($value, $limit = null, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new \b8\Exception\HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $add = '';

        if ($limit) {
            $add .= ' LIMIT ' . $limit;
        }

        $query = 'SELECT COUNT(*) AS cnt FROM build WHERE status = :status' . $add;
        $stmt = \b8\Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':status', $value);

        if ($stmt->execute()) {
            $res    = $stmt->fetch(\PDO::FETCH_ASSOC);
            $count  = (int)$res['cnt'];
        } else {
            $count = 0;
        }

        $query = 'SELECT * FROM build WHERE status = :status' . $add;
        $stmt = \b8\Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':status', $value);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new \PHPCI\Model\Build($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }
}
