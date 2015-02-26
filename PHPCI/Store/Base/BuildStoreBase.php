<?php

/**
 * Build base store for table: build
 */

namespace PHPCI\Store\Base;

use b8\Database;
use b8\Exception\HttpException;
use PHPCI\Store;
use PHPCI\Model\Build;

/**
 * Build Base Store
 */
class BuildStoreBase extends Store
{
    protected $tableName   = 'build';
    protected $modelName   = '\PHPCI\Model\Build';
    protected $primaryKey  = 'id';

    /**
     * Returns a Build model by primary key.
     * @param mixed $value
     * @param string $useConnection
     * @return \@appNamespace\Model\Build|null
     */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }

    /**
     * Returns a Build model by Id.
     * @param mixed $value
     * @param string $useConnection
     * @throws HttpException
     * @return \@appNamespace\Model\Build|null
     */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = 'SELECT * FROM `build` WHERE `id` = :id LIMIT 1';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':id', $value);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new Build($data);
            }
        }

        return null;
    }

    /**
     * Returns an array of Build models by ProjectId.
     * @param mixed $value
     * @param int $limit
     * @param string $useConnection
     * @throws HttpException
     * @return array
     */
    public function getByProjectId($value, $limit = 1000, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }


        $query = 'SELECT * FROM `build` WHERE `project_id` = :project_id LIMIT :limit';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':project_id', $value);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Build($item);
            };
            $rtn = array_map($map, $res);

            $count = count($rtn);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }

    /**
     * Returns an array of Build models by Status.
     * @param mixed $value
     * @param int $limit
     * @param string $useConnection
     * @throws HttpException
     * @return array
     */
    public function getByStatus($value, $limit = 1000, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }


        $query = 'SELECT * FROM `build` WHERE `status` = :status LIMIT :limit';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':status', $value);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Build($item);
            };
            $rtn = array_map($map, $res);

            $count = count($rtn);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }
}
