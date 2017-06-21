<?php

/**
 * User base model for table: user
 */

namespace Kiboko\Component\ContinuousIntegration\Model\Base;

use Kiboko\Component\ContinuousIntegration\Model;

/**
 * User Base Model
 */
class UserBase extends Model
{
    /**
     * @var array
     */
    public static $sleepable = [];

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
        'email' => array('unique' => true, 'columns' => 'email'),
        'name' => array('columns' => 'name'),
    );

    /**
     * @var array
     */
    public $foreignKeys = [];

    /**
     * Get the value of Id / id.
     *
     * @return int
     */
    public function getId(): int
    {
        $rtn    = $this->data['id'];

        return $rtn;
    }

    /**
     * Get the value of Email / email.
     *
     * @return string
     */
    public function getEmail(): string
    {
        $rtn    = $this->data['email'];

        return $rtn;
    }

    /**
     * Get the value of Hash / hash.
     *
     * @return string
     */
    public function getHash(): string
    {
        $rtn    = $this->data['hash'];

        return $rtn;
    }

    /**
     * Get the value of IsAdmin / is_admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        $rtn    = $this->data['is_admin'];

        return $rtn;
    }

    /**
     * Get the value of IsAdmin / is_admin.
     *
     * @return bool
     * @deprecated
     */
    public function getIsAdmin(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Get the value of Name / name.
     *
     * @return string
     */
    public function getName(): string
    {
        $rtn    = $this->data['name'];

        return $rtn;
    }

    /**
     * Set the value of Id / id.
     *
     * Must not be null.
     *
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->_validateNotNull('Id', $id);
        $this->_validateInt('Id', $id);

        if ($this->data['id'] === $id) {
            return;
        }

        $this->data['id'] = $id;

        $this->_setModified('id');
    }

    /**
     * Set the value of Email / email.
     *
     * Must not be null.
     *
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->_validateNotNull('Email', $email);
        $this->_validateString('Email', $email);

        if ($this->data['email'] === $email) {
            return;
        }

        $this->data['email'] = $email;

        $this->_setModified('email');
    }

    /**
     * Set the value of Hash / hash.
     *
     * Must not be null.
     *
     * @param string $hash
     */
    public function setHash(string $hash): void
    {
        $this->_validateNotNull('Hash', $hash);
        $this->_validateString('Hash', $hash);

        if ($this->data['hash'] === $hash) {
            return;
        }

        $this->data['hash'] = $hash;

        $this->_setModified('hash');
    }

    /**
     * Set the value of IsAdmin / is_admin.
     *
     * Must not be null.
     *
     * @param bool $isAdmin
     */
    public function setIsAdmin(bool $isAdmin): void
    {
        $this->_validateNotNull('IsAdmin', $isAdmin);
        $this->_validateInt('IsAdmin', $isAdmin);

        if ($this->data['is_admin'] === $isAdmin) {
            return;
        }

        $this->data['is_admin'] = $isAdmin;

        $this->_setModified('is_admin');
    }

    /**
     * Set the value of Name / name.
     *
     * Must not be null.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->_validateNotNull('Name', $name);
        $this->_validateString('Name', $name);

        if ($this->data['name'] === $name) {
            return;
        }

        $this->data['name'] = $name;

        $this->_setModified('name');
    }
}
