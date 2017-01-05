<?php

/**
 * BuildMeta base store for table: build_meta

 */

namespace PHPCI\Store\Base;

use Block8\Database\Connection;
use PHPCI\Store;
use PHPCI\Store\BuildMetaStore;
use PHPCI\Model\BuildMeta;
use PHPCI\Model\BuildMetaCollection;

/**
 * BuildMeta Base Store
 */
class BuildMetaStoreBase extends Store
{
    /**
     * @var BuildMetaStore $instance
     */
    protected static $instance = null;

    protected $table = 'build_meta';
    protected $model = 'PHPCI\Model\BuildMeta';
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return BuildMetaStore
     */
    public static function load() : BuildMetaStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new BuildMetaStore(Connection::get());
        }

        return self::$instance;
    }

    /**
    * @param $value
    * @return BuildMeta|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a BuildMeta object by Id.
     * @param $value
     * @return BuildMeta|null
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
     * Get all BuildMeta objects by ProjectId.
     * @return \PHPCI\Model\BuildMetaCollection
     */
    public function getByProjectId($value, $limit = null)
    {
        return $this->where('project_id', $value)->get($limit);
    }

    /**
     * Gets the total number of BuildMeta by ProjectId value.
     * @return int
     */
    public function getTotalByProjectId($value) : int
    {
        return $this->where('project_id', $value)->count();
    }

    /**
     * Get all BuildMeta objects by BuildId.
     * @return \PHPCI\Model\BuildMetaCollection
     */
    public function getByBuildId($value, $limit = null)
    {
        return $this->where('build_id', $value)->get($limit);
    }

    /**
     * Gets the total number of BuildMeta by BuildId value.
     * @return int
     */
    public function getTotalByBuildId($value) : int
    {
        return $this->where('build_id', $value)->count();
    }
}
