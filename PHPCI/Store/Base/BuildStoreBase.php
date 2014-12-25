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
     * Get a Build by primary key.
     * @param mixed $value Primary key.
     * @param string $useConnection Connection to use (read / write)
     * @return \PHPCI\Model\Build|null
     */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }

    /**
     * Get a Build by Id.
     * @param mixed $value.
     * @param string $useConnection Connection to use (read / write)
     * @throws \b8\Exception\HttpException
     * @return \PHPCI\Model\Build|null;
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
     * Get an array of Build by ProjectId.
     * @param mixed $value.
     * @param int $limit
     * @param string $useConnection Connection to use (read / write)
     * @throws \b8\Exception\HttpException
     * @return \PHPCI\Model\Build[]
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

        $query = 'SELECT * FROM `build` WHERE `project_id` = :project_id' . $add;
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':project_id', $value);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Build($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }

    /**
     * Get an array of Build by Status.
     * @param mixed $value.
     * @param int $limit
     * @param string $useConnection Connection to use (read / write)
     * @throws \b8\Exception\HttpException
     * @return \PHPCI\Model\Build[]
     */
    public function getByStatus($value, $limit = null, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $add = '';

        if ($limit) {
            $add .= ' LIMIT ' . $limit;
        }

        $count = null;

        $query = 'SELECT * FROM `build` WHERE `status` = :status' . $add;
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':status', $value);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Build($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }
}
