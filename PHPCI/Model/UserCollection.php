<?php

/**
 * User model collection
 */

namespace PHPCI\Model;

use Block8\Database\Model\Collection;

/**
 * User Model Collection
 */
class UserCollection extends Collection
{
    /**
     * Add a User model to the collection.
     * @param string $key
     * @param User $value
     * @return UserCollection
     */
    public function addUser($key, User $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return User|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
