<?php

/**
 * Project base store for table: project
 */

namespace PHPCI\Store\Base;

use b8\Database;
use b8\Exception\HttpException;
use PHPCI\Store;
use PHPCI\Model\Project;

/**
 * Project Base Store
 */
class ProjectStoreBase extends Store
{
    protected $tableName   = 'project';
    protected $modelName   = '\PHPCI\Model\Project';
    protected $primaryKey  = 'id';

    /**
     * Get a Project by primary key.
     * @param mixed $value Primary key.
     * @param string $useConnection Connection to use (read / write)
     * @return \PHPCI\Model\Project|null
     */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }

    /**
     * Get a Project by Id.
     * @param mixed $value.
     * @param string $useConnection Connection to use (read / write)
     * @throws \b8\Exception\HttpException
     * @return \PHPCI\Model\Project|null;
     */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = 'SELECT * FROM `project` WHERE `id` = :id LIMIT 1';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':id', $value);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new Project($data);
            }
        }

        return null;
    }

    /**
     * Get an array of Project by Title.
     * @param mixed $value.
     * @param int $limit
     * @param string $useConnection Connection to use (read / write)
     * @throws \b8\Exception\HttpException
     * @return \PHPCI\Model\Project[]
     */
    public function getByTitle($value, $limit = null, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $add = '';

        if ($limit) {
            $add .= ' LIMIT ' . $limit;
        }

        $count = null;

        $query = 'SELECT * FROM `project` WHERE `title` = :title' . $add;
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':title', $value);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Project($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }
}
