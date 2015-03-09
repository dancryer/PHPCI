<?php

/**
 * User base store for table: user
 */

namespace PHPCI\Store\Base;

use b8\Database;
use b8\Exception\HttpException;
use PHPCI\Store;
use PHPCI\Model\User;

/**
 * User Base Store
 */
class UserStoreBase extends Store
{
    protected $tableName   = 'user';
    protected $modelName   = '\PHPCI\Model\User';
    protected $primaryKey  = 'id';

    /**
     * Returns a User model by primary key.
     * @param mixed $value
     * @param string $useConnection
     * @return \@appNamespace\Model\User|null
     */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }

    /**
     * Returns a User model by Id.
     * @param mixed $value
     * @param string $useConnection
     * @throws HttpException
     * @return \@appNamespace\Model\User|null
     */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = 'SELECT * FROM `user` WHERE `id` = :id LIMIT 1';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':id', $value);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new User($data);
            }
        }

        return null;
    }

    /**
     * Returns a User model by Email.
     * @param mixed $value
     * @param string $useConnection
     * @throws HttpException
     * @return \@appNamespace\Model\User|null
     */
    public function getByEmail($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = 'SELECT * FROM `user` WHERE `email` = :email LIMIT 1';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':email', $value);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new User($data);
            }
        }

        return null;
    }
}
