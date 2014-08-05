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

    public function createUser($name, $emailAddress, $password, $isAdmin = false)
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($emailAddress);
        $user->setHash(password_hash($password, PASSWORD_DEFAULT));
        $user->setIsAdmin(($isAdmin ? 1 : 0));

        return $this->store->save($user);
    }

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

    public function deleteUser(User $user)
    {
        return $this->store->delete($user);
    }
}
