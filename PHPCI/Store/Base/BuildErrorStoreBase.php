<?php

/**
 * BuildError base store for table: build_error

 */

namespace PHPCI\Store\Base;

use Block8\Database\Connection;
use PHPCI\Store;
use PHPCI\Store\BuildErrorStore;
use PHPCI\Model\BuildError;
use PHPCI\Model\BuildErrorCollection;

/**
 * BuildError Base Store
 */
class BuildErrorStoreBase extends Store
{
    /**
     * @var BuildErrorStore $instance
     */
    protected static $instance = null;

    protected $table = 'build_error';
    protected $model = 'PHPCI\Model\BuildError';
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return BuildErrorStore
     */
    public static function load() : BuildErrorStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new BuildErrorStore(Connection::get());
        }

        return self::$instance;
    }

    /**
    * @param $value
    * @return BuildError|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a BuildError object by Id.
     * @param $value
     * @return BuildError|null
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
     * Get all BuildError objects by BuildId.
     * @return \PHPCI\Model\BuildErrorCollection
     */
    public function getByBuildId($value, $limit = null)
    {
        return $this->where('build_id', $value)->get($limit);
    }

    /**
     * Gets the total number of BuildError by BuildId value.
     * @return int
     */
    public function getTotalByBuildId($value) : int
    {
        return $this->where('build_id', $value)->count();
    }
}
