<?php

/**
 * Project base store for table: project

 */

namespace PHPCI\Store\Base;

use Block8\Database\Connection;
use PHPCI\Store;
use PHPCI\Store\ProjectStore;
use PHPCI\Model\Project;
use PHPCI\Model\ProjectCollection;

/**
 * Project Base Store
 */
class ProjectStoreBase extends Store
{
    /**
     * @var ProjectStore $instance
     */
    protected static $instance = null;

    protected $table = 'project';
    protected $model = 'PHPCI\Model\Project';
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return ProjectStore
     */
    public static function load() : ProjectStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProjectStore(Connection::get());
        }

        return self::$instance;
    }

    /**
    * @param $value
    * @return Project|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a Project object by Id.
     * @param $value
     * @return Project|null
     */
    public function getById(int $value)
    {
        // This is the primary key, so try and get from cache:
        $cacheResult = $this->cacheGet($value);

        if (!empty($cacheResult)) {
            return $cacheResult;
        }

        $rtn = $this->where('id', $value)->first();
        $this->cacheSet($value, $rtn);

        return $rtn;
    }

    /**
     * Get all Project objects by Title.
     * @return \PHPCI\Model\ProjectCollection
     */
    public function getByTitle($value, $limit = null)
    {
        return $this->where('title', $value)->get($limit);
    }

    /**
     * Gets the total number of Project by Title value.
     * @return int
     */
    public function getTotalByTitle($value) : int
    {
        return $this->where('title', $value)->count();
    }

    /**
     * Get all Project objects by GroupId.
     * @return \PHPCI\Model\ProjectCollection
     */
    public function getByGroupId($value, $limit = null)
    {
        return $this->where('group_id', $value)->get($limit);
    }

    /**
     * Gets the total number of Project by GroupId value.
     * @return int
     */
    public function getTotalByGroupId($value) : int
    {
        return $this->where('group_id', $value)->count();
    }
}
