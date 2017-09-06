<?php

/**
 * Build base store for table: build

 */

namespace PHPCI\Store\Base;

use Block8\Database\Connection;
use PHPCI\Store;
use PHPCI\Store\BuildStore;
use PHPCI\Model\Build;
use PHPCI\Model\BuildCollection;

/**
 * Build Base Store
 */
class BuildStoreBase extends Store
{
    /**
     * @var BuildStore $instance
     */
    protected static $instance = null;

    protected $table = 'build';
    protected $model = 'PHPCI\Model\Build';
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return BuildStore
     */
    public static function load() : BuildStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new BuildStore(Connection::get());
        }

        return self::$instance;
    }

    /**
    * @param $value
    * @return Build|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a Build object by Id.
     * @param $value
     * @return Build|null
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
     * Get all Build objects by ProjectId.
     * @return \PHPCI\Model\BuildCollection
     */
    public function getByProjectId($value, $limit = null)
    {
        return $this->where('project_id', $value)->get($limit);
    }

    /**
     * Gets the total number of Build by ProjectId value.
     * @return int
     */
    public function getTotalByProjectId($value) : int
    {
        return $this->where('project_id', $value)->count();
    }

    /**
     * Get all Build objects by Status.
     * @return \PHPCI\Model\BuildCollection
     */
    public function getByStatus($value, $limit = null)
    {
        return $this->where('status', $value)->get($limit);
    }

    /**
     * Gets the total number of Build by Status value.
     * @return int
     */
    public function getTotalByStatus($value) : int
    {
        return $this->where('status', $value)->count();
    }
}
