<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Service;

use PHPCI\Model\User;
use PHPCI\Store\UserStore;

/**
 * The user service handles the creation, modification and deletion of users.
 * Class UserService
 * @package PHPCI\Service
 */
class UserService
{
    /**
     * @var \PHPCI\Store\UserStore
     */
    protected $store;

    /**
     * @param UserStore $store
     */
    public function __construct(UserStore $store)
    {
        $this->store = $store;
    }

    /**
     * Create a new user within PHPCI.
     * @param $name
     * @param $emailAddress
     * @param $password
     * @param bool $isAdmin
     * @return \PHPCI\Model\User
     */
    public function createUser($name, $emailAddress, $password, $isAdmin = false)
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($emailAddress);
        $user->setHash(password_hash($password, PASSWORD_DEFAULT));
        $user->setIsAdmin(($isAdmin ? 1 : 0));

        return $this->store->save($user);
    }

    /**
     * Update a user.
     * @param User $user
     * @param $name
     * @param $emailAddress
     * @param null $password
     * @param null $isAdmin
     * @return \PHPCI\Model\User
     */
    public function updateUser(User $user, $name, $emailAddress, $password = null, $isAdmin = null)
    {
        $user->setName($name);
        $user->setEmail($emailAddress);

        if (!empty($password)) {
            $user->setHash(password_hash($password, PASSWORD_DEFAULT));
        }

        if (!is_null($isAdmin)) {
            $user->setIsAdmin(($isAdmin ? 1 : 0));
        }

        return $this->store->save($user);
    }

    /**
     * Delete a user.
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user)
    {
        return $this->store->delete($user);
    }
}
