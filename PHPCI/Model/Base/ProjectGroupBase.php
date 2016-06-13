<?php

/**
 * ProjectGroup base model for table: project_group
 */

namespace PHPCI\Model\Base;

use PHPCI\Model;
use b8\Store\Factory;

/**
 * ProjectGroup Base Model
 */
class ProjectGroupBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'project_group';

    /**
    * @var string
    */
    protected $modelName = 'ProjectGroup';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'title' => null,
    );

    /**
    * @var array
    */
    protected $getters = array(
        // Direct property getters:
        'id' => 'getId',
        'title' => 'getTitle',

        // Foreign key getters:
    );

    /**
    * @var array
    */
    protected $setters = array(
        // Direct property setters:
        'id' => 'setId',
        'title' => 'setTitle',

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
        'title' => array(
            'type' => 'varchar',
            'length' => 100,
            'default' => null,
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
    * Set the value of Title / title.
    *
    * Must not be null.
    * @param $value string
    */
    public function setTitle($value)
    {
        $this->_validateNotNull('Title', $value);
        $this->_validateString('Title', $value);

        if ($this->data['title'] === $value) {
            return;
        }

        $this->data['title'] = $value;

        $this->_setModified('title');
    }

    /**
     * Get Project models by GroupId for this ProjectGroup.
     *
     * @uses \PHPCI\Store\ProjectStore::getByGroupId()
     * @uses \PHPCI\Model\Project
     * @return \PHPCI\Model\Project[]
     */
    public function getGroupProjects()
    {
        return Factory::getStore('Project', 'PHPCI')->getByGroupId($this->getId());
    }
}
