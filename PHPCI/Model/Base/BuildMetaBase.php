<?php

/**
 * BuildMeta base model for table: build_meta
 */

namespace PHPCI\Model\Base;

use b8\Model;

/**
 * BuildMeta Base Model
 */
class BuildMetaBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'build_meta';

    /**
    * @var string
    */
    protected $modelName = 'BuildMeta';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'build_id' => null,
        'key' => null,
        'value' => null,
     );

    /**
    * @var array
    */
    protected $getters = array(
        'id' => 'getId',
        'build_id' => 'getBuildId',
        'key' => 'getKey',
        'value' => 'getValue',
        'Build' => 'getBuild',
     );

    /**
    * @var array
    */
    protected $setters = array(
        'id' => 'setId',
        'build_id' => 'setBuildId',
        'key' => 'setKey',
        'value' => 'setValue',
        'Build' => 'setBuild',
     );

    /**
    * @var array
    */
    public $columns = array(
        'id' => array(
            'type' => 'int',
            'length' => '10',
            'primary_key' => true,
            'auto_increment' => true,
            ),
        'build_id' => array(
            'type' => 'int',
            'length' => '11',
            ),
        'key' => array(
            'type' => 'varchar',
            'length' => '255',
            ),
        'value' => array(
            'type' => 'text',
            'length' => '',
            'nullable' => true,
            ),
     );

    /**
    * @var array
    */
    public $indexes = array(
            'PRIMARY' => array('unique' => true, 'columns' => 'id'),
            'idx_meta_id' => array('unique' => true, 'columns' => 'build_id, key'),
     );

    /**
    * @var array
    */
    public $foreignKeys = array(
            'fk_meta_build_id' => array(
                'local_col' => 'build_id',
                'update' => 'CASCADE',
                'delete' => 'CASCADE',
                'table' => 'build',
                'col' => 'id'
                ),
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
    * Get the value of BuildId / build_id.
    *
    * @return int
    */
    public function getBuildId()
    {
        $rtn    = $this->data['build_id'];

        
        return $rtn;
    }

    /**
    * Get the value of Key / key.
    *
    * @return string
    */
    public function getKey()
    {
        $rtn    = $this->data['key'];

        
        return $rtn;
    }

    /**
    * Get the value of Value / value.
    *
    * @return string
    */
    public function getValue()
    {
        $rtn    = $this->data['value'];

        
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
    * Set the value of BuildId / build_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setBuildId($value)
    {
        $this->_validateNotNull('BuildId', $value);
        $this->_validateInt('BuildId', $value);
        if ($this->data['build_id'] == $value) {
            return;
        }

        $this->data['build_id'] = $value;

        $this->_setModified('build_id');
    }

    /**
    * Set the value of Key / key.
    *
    * Must not be null.
    * @param $value string
    */
    public function setKey($value)
    {
        $this->_validateNotNull('Key', $value);
        $this->_validateString('Key', $value);
        if ($this->data['key'] == $value) {
            return;
        }

        $this->data['key'] = $value;

        $this->_setModified('key');
    }

    /**
    * Set the value of Value / value.
    *
    * @param $value string
    */
    public function setValue($value)
    {

        $this->_validateString('Value', $value);
        if ($this->data['value'] == $value) {
            return;
        }

        $this->data['value'] = $value;

        $this->_setModified('value');
    }

    /**
     * Get the Build model for this BuildMeta by Id.
     *
     * @uses \PHPCI\Store\BuildStore::getById()
     * @uses \PHPCI\Model\Build
     * @return \PHPCI\Model\Build
     */
    public function getBuild()
    {
        $key = $this->getBuildId();

        if (empty($key)) {
            return null;
        }

        $cacheKey   = 'Cache.Build.' . $key;
        $rtn        = $this->cache->get($cacheKey, null);

        if (empty($rtn)) {
            $rtn    = \b8\Store\Factory::getStore('Build')->getById($key);
            $this->cache->set($cacheKey, $rtn);
        }

        return $rtn;
    }

    /**
    * Set Build - Accepts an ID, an array representing a Build or a Build model.
    *
    * @param $value mixed
    */
    public function setBuild($value)
    {
        // Is this an instance of Build?
        if ($value instanceof \PHPCI\Model\Build) {
            return $this->setBuildObject($value);
        }

        // Is this an array representing a Build item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setBuildId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setBuildId($value);
    }

    /**
    * Set Build - Accepts a Build model.
    * 
    * @param $value \PHPCI\Model\Build
    */
    public function setBuildObject(\PHPCI\Model\Build $value)
    {
        return $this->setBuildId($value->getId());
    }
}
