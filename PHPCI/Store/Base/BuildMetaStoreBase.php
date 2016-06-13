<?php

/**
 * BuildMeta base store for table: build_meta
 */

namespace PHPCI\Store\Base;

use b8\Database;
use b8\Exception\HttpException;
use PHPCI\Store;
use PHPCI\Model\BuildMeta;

/**
 * BuildMeta Base Store
 */
class BuildMetaStoreBase extends Store
{
    protected $tableName   = 'build_meta';
    protected $modelName   = '\PHPCI\Model\BuildMeta';
    protected $primaryKey  = 'id';

    /**
     * Get a BuildMeta by primary key (Id)
     */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }

    /**
     * Get a single BuildMeta by Id.
     * @return null|BuildMeta
     */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = 'SELECT * FROM `build_meta` WHERE `id` = :id LIMIT 1';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':id', $value);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new BuildMeta($data);
            }
        }

        return null;
    }

    /**
     * Get multiple BuildMeta by ProjectId.
     * @return array
     */
    public function getByProjectId($value, $limit = 1000, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }


        $query = 'SELECT * FROM `build_meta` WHERE `project_id` = :project_id LIMIT :limit';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':project_id', $value);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new BuildMeta($item);
            };
            $rtn = array_map($map, $res);

            $count = count($rtn);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }

    /**
     * Get multiple BuildMeta by BuildId.
     * @return array
     */
    public function getByBuildId($value, $limit = 1000, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }


        $query = 'SELECT * FROM `build_meta` WHERE `build_id` = :build_id LIMIT :limit';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':build_id', $value);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new BuildMeta($item);
            };
            $rtn = array_map($map, $res);

            $count = count($rtn);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }
}
