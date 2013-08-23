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
        'access_information' => null,
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
        'access_information' => 'getAccessInformation',
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
        'access_information' => 'setAccessInformation',
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
        'access_information' => array(
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
        $rtn    = $this->data['id'];

        
        return $rtn;
    }

    /**
    * Get the value of Title / title.
    *
    * @return string
    */
    public function getTitle()
    {
        $rtn    = $this->data['title'];

        
        return $rtn;
    }

    /**
    * Get the value of Reference / reference.
    *
    * @return string
    */
    public function getReference()
    {
        $rtn    = $this->data['reference'];

        
        return $rtn;
    }

    /**
     * Get the value of Domain / domain.
     *
     * @return string
     */
    public function getAccessInformation()
    {
        $rtn    = unserialize($this->data['access_information']);


        return $rtn;
    }

    /**
    * Get the value of GitKey / git_key.
    *
    * @return string
    */
    public function getGitKey()
    {
        $rtn    = $this->data['git_key'];

        
        return $rtn;
    }

    /**
    * Get the value of Type / type.
    *
    * @return string
    */
    public function getType()
    {
        $rtn    = $this->data['type'];

        
        return $rtn;
    }

    /**
    * Get the value of Token / token.
    *
    * @return string
    */
    public function getToken()
    {
        $rtn    = $this->data['token'];

        
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
        if ($this->data['id'] == $value) {
            return;
        }

        $this->data['id'] = $value;

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
        if ($this->data['title'] == $value) {
            return;
        }

        $this->data['title'] = $value;

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
        if ($this->data['reference'] == $value) {
            return;
        }

        $this->data['reference'] = $value;

        $this->_setModified('reference');
    }

    /**
     * Set the value of Domain / domain.
     *
     * Must not be null.
     * @param $value string
     */
    public function setAccessInformation($value)
    {
        $this->_validateNotNull('AccessInformation', $value);
        $this->_validateString('AccessInformation', $value);
        if ($this->data['access_information'] == $value) {
            return;
        }

        $this->data['access_information'] = $value;

        $this->_setModified('access_information');
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
        if ($this->data['git_key'] == $value) {
            return;
        }

        $this->data['git_key'] = $value;

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
        if ($this->data['type'] == $value) {
            return;
        }

        $this->data['type'] = $value;

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
        if ($this->data['token'] == $value) {
            return;
        }

        $this->data['token'] = $value;

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
