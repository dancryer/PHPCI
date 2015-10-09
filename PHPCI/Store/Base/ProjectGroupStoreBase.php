<?php

/**
 * ProjectGroup base store for table: project_group
 */

namespace PHPCI\Store\Base;

use b8\Database;
use b8\Exception\HttpException;
use PHPCI\Store;
use PHPCI\Model\ProjectGroup;

/**
 * ProjectGroup Base Store
 */
class ProjectGroupStoreBase extends Store
{
    protected $tableName   = 'project_group';
    protected $modelName   = '\PHPCI\Model\ProjectGroup';
    protected $primaryKey  = 'id';

    /**
     * Get a ProjectGroup by primary key (Id)
     */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }

    /**
     * Get a single ProjectGroup by Id.
     * @return null|ProjectGroup
     */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = 'SELECT * FROM `project_group` WHERE `id` = :id LIMIT 1';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':id', $value);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new ProjectGroup($data);
            }
        }

        return null;
    }
}
