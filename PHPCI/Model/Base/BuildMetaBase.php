<?php

/**
 * BuildMeta base model for table: build_meta
 */

namespace PHPCI\Model\Base;

use PHPCI\Model;
use b8\Store\Factory;

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
        'project_id' => null,
        'build_id' => null,
        'meta_key' => null,
        'meta_value' => null,
    );

    /**
    * @var array
    */
    protected $getters = array(
        // Direct property getters:
        'id' => 'getId',
        'project_id' => 'getProjectId',
        'build_id' => 'getBuildId',
        'meta_key' => 'getMetaKey',
        'meta_value' => 'getMetaValue',

        // Foreign key getters:
        'Project' => 'getProject',
        'Build' => 'getBuild',
    );

    /**
    * @var array
    */
    protected $setters = array(
        // Direct property setters:
        'id' => 'setId',
        'project_id' => 'setProjectId',
        'build_id' => 'setBuildId',
        'meta_key' => 'setMetaKey',
        'meta_value' => 'setMetaValue',

        // Foreign key setters:
        'Project' => 'setProject',
        'Build' => 'setBuild',
    );

    /**
    * @var array
    */
    public $columns = array(
        'id' => array(
            'type' => 'int',
            'length' => 10,
            'primary_key' => true,
            'auto_increment' => true,
            'default' => null,
        ),
        'project_id' => array(
            'type' => 'int',
            'length' => 11,
            'default' => null,
        ),
        'build_id' => array(
            'type' => 'int',
            'length' => 11,
            'default' => null,
        ),
        'meta_key' => array(
            'type' => 'varchar',
            'length' => 250,
            'default' => null,
        ),
        'meta_value' => array(
            'type' => 'text',
            'default' => null,
        ),
    );

    /**
    * @var array
    */
    public $indexes = array(
            'PRIMARY' => array('unique' => true, 'columns' => 'id'),
            'idx_meta_id' => array('unique' => true, 'columns' => 'build_id, meta_key'),
            'project_id' => array('columns' => 'project_id'),
    );

    /**
    * @var array
    */
    public $foreignKeys = array(
            'build_meta_ibfk_1' => array(
                'local_col' => 'project_id',
                'update' => 'CASCADE',
                'delete' => 'CASCADE',
                'table' => 'project',
                'col' => 'id'
                ),
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
    * Get the value of ProjectId / project_id.
    *
    * @return int
    */
    public function getProjectId()
    {
        $rtn    = $this->data['project_id'];

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
    * Get the value of MetaKey / meta_key.
    *
    * @return string
    */
    public function getMetaKey()
    {
        $rtn    = $this->data['meta_key'];

        return $rtn;
    }

    /**
    * Get the value of MetaValue / meta_value.
    *
    * @return string
    */
    public function getMetaValue()
    {
        $rtn    = $this->data['meta_value'];

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
    * Set the value of ProjectId / project_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setProjectId($value)
    {
        $this->_validateNotNull('ProjectId', $value);
        $this->_validateInt('ProjectId', $value);

        if ($this->data['project_id'] === $value) {
            return;
        }

        $this->data['project_id'] = $value;

        $this->_setModified('project_id');
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

        if ($this->data['build_id'] === $value) {
            return;
        }

        $this->data['build_id'] = $value;

        $this->_setModified('build_id');
    }

    /**
    * Set the value of MetaKey / meta_key.
    *
    * Must not be null.
    * @param $value string
    */
    public function setMetaKey($value)
    {
        $this->_validateNotNull('MetaKey', $value);
        $this->_validateString('MetaKey', $value);

        if ($this->data['meta_key'] === $value) {
            return;
        }

        $this->data['meta_key'] = $value;

        $this->_setModified('meta_key');
    }

    /**
    * Set the value of MetaValue / meta_value.
    *
    * Must not be null.
    * @param $value string
    */
    public function setMetaValue($value)
    {
        $this->_validateNotNull('MetaValue', $value);
        $this->_validateString('MetaValue', $value);

        if ($this->data['meta_value'] === $value) {
            return;
        }

        $this->data['meta_value'] = $value;

        $this->_setModified('meta_value');
    }

    /**
     * Get the Project model for this BuildMeta by Id.
     *
     * @uses \PHPCI\Store\ProjectStore::getById()
     * @uses \PHPCI\Model\Project
     * @return \PHPCI\Model\Project
     */
    public function getProject()
    {
        $key = $this->getProjectId();

        if (empty($key)) {
            return null;
        }

        $cacheKey   = 'Cache.Project.' . $key;
        $rtn        = $this->cache->get($cacheKey, null);

        if (empty($rtn)) {
            $rtn    = Factory::getStore('Project', 'PHPCI')->getById($key);
            $this->cache->set($cacheKey, $rtn);
        }

        return $rtn;
    }

    /**
    * Set Project - Accepts an ID, an array representing a Project or a Project model.
    *
    * @param $value mixed
    */
    public function setProject($value)
    {
        // Is this an instance of Project?
        if ($value instanceof \PHPCI\Model\Project) {
            return $this->setProjectObject($value);
        }

        // Is this an array representing a Project item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setProjectId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setProjectId($value);
    }

    /**
    * Set Project - Accepts a Project model.
    * 
    * @param $value \PHPCI\Model\Project
    */
    public function setProjectObject(\PHPCI\Model\Project $value)
    {
        return $this->setProjectId($value->getId());
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
            $rtn    = Factory::getStore('Build', 'PHPCI')->getById($key);
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
