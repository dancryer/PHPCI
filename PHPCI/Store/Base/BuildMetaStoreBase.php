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
     * Get a BuildMeta by primary key.
     * @param mixed $value Primary key.
     * @param string $useConnection Connection to use (read / write)
     * @return \PHPCI\Model\BuildMeta|null
     */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }

    /**
     * Get a BuildMeta by Id.
     * @param mixed $value.
     * @param string $useConnection Connection to use (read / write)
     * @throws \b8\Exception\HttpException
     * @return \PHPCI\Model\BuildMeta|null;
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
     * Get an array of BuildMeta by ProjectId.
     * @param mixed $value.
     * @param int $limit
     * @param string $useConnection Connection to use (read / write)
     * @throws \b8\Exception\HttpException
     * @return \PHPCI\Model\BuildMeta[]
     */
    public function getByProjectId($value, $limit = null, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $add = '';

        if ($limit) {
            $add .= ' LIMIT ' . $limit;
        }

        $count = null;

        $query = 'SELECT * FROM `build_meta` WHERE `project_id` = :project_id' . $add;
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':project_id', $value);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new BuildMeta($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }

    /**
     * Get an array of BuildMeta by BuildId.
     * @param mixed $value.
     * @param int $limit
     * @param string $useConnection Connection to use (read / write)
     * @throws \b8\Exception\HttpException
     * @return \PHPCI\Model\BuildMeta[]
     */
    public function getByBuildId($value, $limit = null, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $add = '';

        if ($limit) {
            $add .= ' LIMIT ' . $limit;
        }

        $count = null;

        $query = 'SELECT * FROM `build_meta` WHERE `build_id` = :build_id' . $add;
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':build_id', $value);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new BuildMeta($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }
}
