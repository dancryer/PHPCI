<?php

/**
 * User base store for table: user

 */

namespace PHPCI\Store\Base;

use Block8\Database\Connection;
use PHPCI\Store;
use PHPCI\Store\UserStore;
use PHPCI\Model\User;
use PHPCI\Model\UserCollection;

/**
 * User Base Store
 */
class UserStoreBase extends Store
{
    /**
     * @var UserStore $instance
     */
    protected static $instance = null;

    protected $table = 'user';
    protected $model = 'PHPCI\Model\User';
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return UserStore
     */
    public static function load() : UserStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserStore(Connection::get());
        }

        return self::$instance;
    }

    /**
    * @param $value
    * @return User|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a User object by Id.
     * @param $value
     * @return User|null
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
     * Get a User object by Email.
     * @param $value
     * @return User|null
     */
    public function getByEmail(string $value)
    {
        return $this->where('email', $value)->first();
    }

    /**
     * Get all User objects by Name.
     * @return \PHPCI\Model\UserCollection
     */
    public function getByName($value, $limit = null)
    {
        return $this->where('name', $value)->get($limit);
    }

    /**
     * Gets the total number of User by Name value.
     * @return int
     */
    public function getTotalByName($value) : int
    {
        return $this->where('name', $value)->count();
    }
}
