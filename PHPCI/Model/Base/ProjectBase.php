<?php

/**
 * Project base model for table: project
 */

namespace PHPCI\Model\Base;

use b8\Model;

/**
 * Project Base Model
 */
class ProjectBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'project';

    /**
    * @var string
    */
    protected $modelName = 'Project';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'title' => null,
        'reference' => null,
        'git_key' => null,
        'type' => null,
        'token' => null,
     );

    /**
    * @var array
    */
    protected $getters = array(
        'id' => 'getId',
        'title' => 'getTitle',
        'reference' => 'getReference',
        'git_key' => 'getGitKey',
        'type' => 'getType',
        'token' => 'getToken',
     );

    /**
    * @var array
    */
    protected $setters = array(
        'id' => 'setId',
        'title' => 'setTitle',
        'reference' => 'setReference',
        'git_key' => 'setGitKey',
        'type' => 'setType',
        'token' => 'setToken',
     );

    /**
    * @var array
    */
    public $columns = array(
        'id' => array(
            'type' => 'int',
            'length' => '11',
            'primary_key' => true,
            'auto_increment' => true,
            ),
        'title' => array(
            'type' => 'varchar',
            'length' => '250',
            ),
        'reference' => array(
            'type' => 'varchar',
            'length' => '250',
            ),
        'git_key' => array(
            'type' => 'text',
            'length' => '',
            ),
        'type' => array(
            'type' => 'varchar',
            'length' => '50',
            ),
        'token' => array(
            'type' => 'varchar',
            'length' => '50',
            'nullable' => true,
            ),
     );

    /**
    * @var array
    */
    public $indexes = array(
            'PRIMARY' => array('unique' => true, 'columns' => 'id'),
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
        $rtn    = $this->_data['id'];

        
        return $rtn;
    }

    /**
    * Get the value of Title / title.
    *
    * @return string
    */
    public function getTitle()
    {
        $rtn    = $this->_data['title'];

        
        return $rtn;
    }

    /**
    * Get the value of Reference / reference.
    *
    * @return string
    */
    public function getReference()
    {
        $rtn    = $this->_data['reference'];

        
        return $rtn;
    }

    /**
    * Get the value of GitKey / git_key.
    *
    * @return string
    */
    public function getGitKey()
    {
        $rtn    = $this->_data['git_key'];

        
        return $rtn;
    }

    /**
    * Get the value of Type / type.
    *
    * @return string
    */
    public function getType()
    {
        $rtn    = $this->_data['type'];

        
        return $rtn;
    }

    /**
    * Get the value of Token / token.
    *
    * @return string
    */
    public function getToken()
    {
        $rtn    = $this->_data['token'];

        
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
        if ($this->_data['id'] === $value) {
            return;
        }

        $this->_data['id'] = $value;

        $this->_setModified('id');
    }

    /**
    * Set the value of Title / title.
    *
    * Must not be null.
    * @param $value string
    */
    public function setTitle($value)
    {
        $this->_validateNotNull('Title', $value);
        $this->_validateString('Title', $value);
        if ($this->_data['title'] === $value) {
            return;
        }

        $this->_data['title'] = $value;

        $this->_setModified('title');
    }

    /**
    * Set the value of Reference / reference.
    *
    * Must not be null.
    * @param $value string
    */
    public function setReference($value)
    {
        $this->_validateNotNull('Reference', $value);
        $this->_validateString('Reference', $value);
        if ($this->_data['reference'] === $value) {
            return;
        }

        $this->_data['reference'] = $value;

        $this->_setModified('reference');
    }

    /**
    * Set the value of GitKey / git_key.
    *
    * Must not be null.
    * @param $value string
    */
    public function setGitKey($value)
    {
        $this->_validateNotNull('GitKey', $value);
        $this->_validateString('GitKey', $value);
        if ($this->_data['git_key'] === $value) {
            return;
        }

        $this->_data['git_key'] = $value;

        $this->_setModified('git_key');
    }

    /**
    * Set the value of Type / type.
    *
    * Must not be null.
    * @param $value string
    */
    public function setType($value)
    {
        $this->_validateNotNull('Type', $value);
        $this->_validateString('Type', $value);
        if ($this->_data['type'] === $value) {
            return;
        }

        $this->_data['type'] = $value;

        $this->_setModified('type');
    }

    /**
    * Set the value of Token / token.
    *
    * @param $value string
    */
    public function setToken($value)
    {

        $this->_validateString('Token', $value);
        if ($this->_data['token'] === $value) {
            return;
        }

        $this->_data['token'] = $value;

        $this->_setModified('token');
    }

    /**
     * Get Build models by ProjectId for this Project.
     *
     * @uses \PHPCI\Store\BuildStore::getByProjectId()
     * @uses \PHPCI\Model\Build
     * @return \PHPCI\Model\Build[]
     */
    public function getProjectBuilds()
    {
        return \b8\Store\Factory::getStore('Build')->getByProjectId($this->getId());
    }
}
