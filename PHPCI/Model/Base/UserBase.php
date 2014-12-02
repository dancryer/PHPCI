<?php

/**
 * User base model for table: user
 */

namespace PHPCI\Model\Base;

use PHPCI\Model;
use b8\Store\Factory;

/**
 * User Base Model
 */
class UserBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'user';

    /**
    * @var string
    */
    protected $modelName = 'User';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'email' => null,
        'hash' => null,
        'is_admin' => null,
        'name' => null,
    );

    /**
    * @var array
    */
    protected $getters = array(
        // Direct property getters:
        'id' => 'getId',
        'email' => 'getEmail',
        'hash' => 'getHash',
        'is_admin' => 'getIsAdmin',
        'name' => 'getName',

        // Foreign key getters:
    );

    /**
    * @var array
    */
    protected $setters = array(
        // Direct property setters:
        'id' => 'setId',
        'email' => 'setEmail',
        'hash' => 'setHash',
        'is_admin' => 'setIsAdmin',
        'name' => 'setName',

        // Foreign key setters:
    );

    /**
    * @var array
    */
    public $columns = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'primary_key' => true,
            'auto_increment' => true,
            'default' => null,
        ),
        'email' => array(
            'type' => 'varchar',
            'length' => 250,
            'default' => null,
        ),
        'hash' => array(
            'type' => 'varchar',
            'length' => 250,
            'default' => null,
        ),
        'is_admin' => array(
            'type' => 'int',
            'length' => 11,
        ),
        'name' => array(
            'type' => 'varchar',
            'length' => 250,
            'default' => null,
        ),
    );

    /**
    * @var array
    */
    public $indexes = array(
            'PRIMARY' => array('unique' => true, 'columns' => 'id'),
            'idx_email' => array('unique' => true, 'columns' => 'email'),
    );

    /**
    * @var array
    */
    public $foreignKeys = array(
    );

    /**
    * Get the value of Id / id.
    *
    * @return int
    */
    public function getId()
    {
        $rtn    = $this->data['id'];

        return $rtn;
    }

    /**
    * Get the value of Email / email.
    *
    * @return string
    */
    public function getEmail()
    {
        $rtn    = $this->data['email'];

        return $rtn;
    }

    /**
    * Get the value of Hash / hash.
    *
    * @return string
    */
    public function getHash()
    {
        $rtn    = $this->data['hash'];

        return $rtn;
    }

    /**
    * Get the value of IsAdmin / is_admin.
    *
    * @return int
    */
    public function getIsAdmin()
    {
        $rtn    = $this->data['is_admin'];

        return $rtn;
    }

    /**
    * Get the value of Name / name.
    *
    * @return string
    */
    public function getName()
    {
        $rtn    = $this->data['name'];

        return $rtn;
    }

    /**
    * Set the value of Id / id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setId($value)
    {
        $this->_validateNotNull('Id', $value);
        $this->_validateInt('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;

        $this->_setModified('id');
    }

    /**
    * Set the value of Email / email.
    *
    * Must not be null.
    * @param $value string
    */
    public function setEmail($value)
    {
        $this->_validateNotNull('Email', $value);
        $this->_validateString('Email', $value);

        if ($this->data['email'] === $value) {
            return;
        }

        $this->data['email'] = $value;

        $this->_setModified('email');
    }

    /**
    * Set the value of Hash / hash.
    *
    * Must not be null.
    * @param $value string
    */
    public function setHash($value)
    {
        $this->_validateNotNull('Hash', $value);
        $this->_validateString('Hash', $value);

        if ($this->data['hash'] === $value) {
            return;
        }

        $this->data['hash'] = $value;

        $this->_setModified('hash');
    }

    /**
    * Set the value of IsAdmin / is_admin.
    *
    * Must not be null.
    * @param $value int
    */
    public function setIsAdmin($value)
    {
        $this->_validateNotNull('IsAdmin', $value);
        $this->_validateInt('IsAdmin', $value);

        if ($this->data['is_admin'] === $value) {
            return;
        }

        $this->data['is_admin'] = $value;

        $this->_setModified('is_admin');
    }

    /**
    * Set the value of Name / name.
    *
    * Must not be null.
    * @param $value string
    */
    public function setName($value)
    {
        $this->_validateNotNull('Name', $value);
        $this->_validateString('Name', $value);

        if ($this->data['name'] === $value) {
            return;
        }

        $this->data['name'] = $value;

        $this->_setModified('name');
    }
}
