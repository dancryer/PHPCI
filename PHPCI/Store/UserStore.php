<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Store;

use PHPCI\Store\Base\UserStoreBase;

/**
* User Store
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Core
*/
class UserStore extends UserStoreBase
{
    /**
     * Returns a User model by Email.
     * @param string $value
     * @param string $useConnection
     * @throws HttpException
     * @return \@appNamespace\Model\User|null
     */
    public function getByLoginOrEmail($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = 'SELECT * FROM `user` WHERE `name` = :value OR `email` = :value LIMIT 1';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':value', $value);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new User($data);
            }
        }

        return null;
    }
}
