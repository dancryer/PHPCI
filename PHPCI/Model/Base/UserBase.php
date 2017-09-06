<?php

/**
 * User base model for table: user
 */

namespace PHPCI\Model\Base;

use DateTime;
use Block8\Database\Query;
use PHPCI\Model;
use PHPCI\Model\User;
use PHPCI\Store;
use PHPCI\Store\UserStore;

/**
 * User Base Model
 */
abstract class UserBase extends Model
{
    protected $table = 'user';
    protected $model = 'User';
    protected $data = [
        'id' => null,
        'email' => null,
        'hash' => null,
        'name' => null,
        'is_admin' => null,
    ];

    protected $getters = [
        'id' => 'getId',
        'email' => 'getEmail',
        'hash' => 'getHash',
        'name' => 'getName',
        'is_admin' => 'getIsAdmin',
    ];

    protected $setters = [
        'id' => 'setId',
        'email' => 'setEmail',
        'hash' => 'setHash',
        'name' => 'setName',
        'is_admin' => 'setIsAdmin',
    ];

    /**
     * Return the database store for this model.
     * @return UserStore
     */
    public static function Store() : UserStore
    {
        return UserStore::load();
    }

    
    /**
     * Get the value of Id / id
     * @return int
     */

     public function getId() : int
     {
        $rtn = $this->data['id'];

        return $rtn;
     }
    
    /**
     * Get the value of Email / email
     * @return string
     */

     public function getEmail() : string
     {
        $rtn = $this->data['email'];

        return $rtn;
     }
    
    /**
     * Get the value of Hash / hash
     * @return string
     */

     public function getHash() : string
     {
        $rtn = $this->data['hash'];

        return $rtn;
     }
    
    /**
     * Get the value of Name / name
     * @return string
     */

     public function getName() : string
     {
        $rtn = $this->data['name'];

        return $rtn;
     }
    
    /**
     * Get the value of IsAdmin / is_admin
     * @return int
     */

     public function getIsAdmin() : int
     {
        $rtn = $this->data['is_admin'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return User
     */
    public function setId(int $value) : User
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of Email / email
     * @param $value string
     * @return User
     */
    public function setEmail(string $value) : User
    {

        if ($this->data['email'] !== $value) {
            $this->data['email'] = $value;
            $this->setModified('email');
        }

        return $this;
    }
    
    /**
     * Set the value of Hash / hash
     * @param $value string
     * @return User
     */
    public function setHash(string $value) : User
    {

        if ($this->data['hash'] !== $value) {
            $this->data['hash'] = $value;
            $this->setModified('hash');
        }

        return $this;
    }
    
    /**
     * Set the value of Name / name
     * @param $value string
     * @return User
     */
    public function setName(string $value) : User
    {

        if ($this->data['name'] !== $value) {
            $this->data['name'] = $value;
            $this->setModified('name');
        }

        return $this;
    }
    
    /**
     * Set the value of IsAdmin / is_admin
     * @param $value int
     * @return User
     */
    public function setIsAdmin(int $value) : User
    {

        if ($this->data['is_admin'] !== $value) {
            $this->data['is_admin'] = $value;
            $this->setModified('is_admin');
        }

        return $this;
    }
    
    
}
