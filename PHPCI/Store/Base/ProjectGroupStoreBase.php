<?php

/**
 * ProjectGroup base store for table: project_group

 */

namespace PHPCI\Store\Base;

use Block8\Database\Connection;
use PHPCI\Store;
use PHPCI\Store\ProjectGroupStore;
use PHPCI\Model\ProjectGroup;
use PHPCI\Model\ProjectGroupCollection;

/**
 * ProjectGroup Base Store
 */
class ProjectGroupStoreBase extends Store
{
    /**
     * @var ProjectGroupStore $instance
     */
    protected static $instance = null;

    protected $table = 'project_group';
    protected $model = 'PHPCI\Model\ProjectGroup';
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return ProjectGroupStore
     */
    public static function load() : ProjectGroupStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProjectGroupStore(Connection::get());
        }

        return self::$instance;
    }

    /**
    * @param $value
    * @return ProjectGroup|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a ProjectGroup object by Id.
     * @param $value
     * @return ProjectGroup|null
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
}
