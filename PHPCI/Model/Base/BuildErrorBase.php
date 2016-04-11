<?php

/**
 * BuildError base model for table: build_error
 */

namespace PHPCI\Model\Base;

use PHPCI\Model;
use b8\Store\Factory;

/**
 * BuildError Base Model
 */
class BuildErrorBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'build_error';

    /**
    * @var string
    */
    protected $modelName = 'BuildError';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'build_id' => null,
        'plugin' => null,
        'file' => null,
        'line_start' => null,
        'line_end' => null,
        'severity' => null,
        'message' => null,
        'created_date' => null,
    );

    /**
    * @var array
    */
    protected $getters = array(
        // Direct property getters:
        'id' => 'getId',
        'build_id' => 'getBuildId',
        'plugin' => 'getPlugin',
        'file' => 'getFile',
        'line_start' => 'getLineStart',
        'line_end' => 'getLineEnd',
        'severity' => 'getSeverity',
        'message' => 'getMessage',
        'created_date' => 'getCreatedDate',

        // Foreign key getters:
        'Build' => 'getBuild',
    );

    /**
    * @var array
    */
    protected $setters = array(
        // Direct property setters:
        'id' => 'setId',
        'build_id' => 'setBuildId',
        'plugin' => 'setPlugin',
        'file' => 'setFile',
        'line_start' => 'setLineStart',
        'line_end' => 'setLineEnd',
        'severity' => 'setSeverity',
        'message' => 'setMessage',
        'created_date' => 'setCreatedDate',

        // Foreign key setters:
        'Build' => 'setBuild',
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
        'build_id' => array(
            'type' => 'int',
            'length' => 11,
            'default' => null,
        ),
        'plugin' => array(
            'type' => 'varchar',
            'length' => 100,
            'default' => null,
        ),
        'file' => array(
            'type' => 'varchar',
            'length' => 250,
            'nullable' => true,
            'default' => null,
        ),
        'line_start' => array(
            'type' => 'int',
            'length' => 11,
            'nullable' => true,
            'default' => null,
        ),
        'line_end' => array(
            'type' => 'int',
            'length' => 11,
            'nullable' => true,
            'default' => null,
        ),
        'severity' => array(
            'type' => 'tinyint',
            'length' => 3,
            'default' => null,
        ),
        'message' => array(
            'type' => 'varchar',
            'length' => 250,
            'default' => null,
        ),
        'created_date' => array(
            'type' => 'datetime',
            'default' => null,
        ),
    );

    /**
    * @var array
    */
    public $indexes = array(
            'PRIMARY' => array('unique' => true, 'columns' => 'id'),
            'build_id' => array('columns' => 'build_id, created_date'),
    );

    /**
    * @var array
    */
    public $foreignKeys = array(
            'build_error_ibfk_1' => array(
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
    * Get the value of Plugin / plugin.
    *
    * @return string
    */
    public function getPlugin()
    {
        $rtn    = $this->data['plugin'];

        return $rtn;
    }

    /**
    * Get the value of File / file.
    *
    * @return string
    */
    public function getFile()
    {
        $rtn    = $this->data['file'];

        return $rtn;
    }

    /**
    * Get the value of LineStart / line_start.
    *
    * @return int
    */
    public function getLineStart()
    {
        $rtn    = $this->data['line_start'];

        return $rtn;
    }

    /**
    * Get the value of LineEnd / line_end.
    *
    * @return int
    */
    public function getLineEnd()
    {
        $rtn    = $this->data['line_end'];

        return $rtn;
    }

    /**
    * Get the value of Severity / severity.
    *
    * @return int
    */
    public function getSeverity()
    {
        $rtn    = $this->data['severity'];

        return $rtn;
    }

    /**
    * Get the value of Message / message.
    *
    * @return string
    */
    public function getMessage()
    {
        $rtn    = $this->data['message'];

        return $rtn;
    }

    /**
    * Get the value of CreatedDate / created_date.
    *
    * @return \DateTime
    */
    public function getCreatedDate()
    {
        $rtn    = $this->data['created_date'];

        if (!empty($rtn)) {
            $rtn    = new \DateTime($rtn);
        }
        
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
    * Set the value of Plugin / plugin.
    *
    * Must not be null.
    * @param $value string
    */
    public function setPlugin($value)
    {
        $this->_validateNotNull('Plugin', $value);
        $this->_validateString('Plugin', $value);

        if ($this->data['plugin'] === $value) {
            return;
        }

        $this->data['plugin'] = $value;

        $this->_setModified('plugin');
    }

    /**
    * Set the value of File / file.
    *
    * @param $value string
    */
    public function setFile($value)
    {
        $this->_validateString('File', $value);

        if ($this->data['file'] === $value) {
            return;
        }

        $this->data['file'] = $value;

        $this->_setModified('file');
    }

    /**
    * Set the value of LineStart / line_start.
    *
    * @param $value int
    */
    public function setLineStart($value)
    {
        $this->_validateInt('LineStart', $value);

        if ($this->data['line_start'] === $value) {
            return;
        }

        $this->data['line_start'] = $value;

        $this->_setModified('line_start');
    }

    /**
    * Set the value of LineEnd / line_end.
    *
    * @param $value int
    */
    public function setLineEnd($value)
    {
        $this->_validateInt('LineEnd', $value);

        if ($this->data['line_end'] === $value) {
            return;
        }

        $this->data['line_end'] = $value;

        $this->_setModified('line_end');
    }

    /**
    * Set the value of Severity / severity.
    *
    * Must not be null.
    * @param $value int
    */
    public function setSeverity($value)
    {
        $this->_validateNotNull('Severity', $value);
        $this->_validateInt('Severity', $value);

        if ($this->data['severity'] === $value) {
            return;
        }

        $this->data['severity'] = $value;

        $this->_setModified('severity');
    }

    /**
    * Set the value of Message / message.
    *
    * Must not be null.
    * @param $value string
    */
    public function setMessage($value)
    {
        $this->_validateNotNull('Message', $value);
        $this->_validateString('Message', $value);

        if ($this->data['message'] === $value) {
            return;
        }

        $this->data['message'] = $value;

        $this->_setModified('message');
    }

    /**
    * Set the value of CreatedDate / created_date.
    *
    * Must not be null.
    * @param $value \DateTime
    */
    public function setCreatedDate($value)
    {
        $this->_validateNotNull('CreatedDate', $value);
        $this->_validateDate('CreatedDate', $value);

        if ($this->data['created_date'] === $value) {
            return;
        }

        $this->data['created_date'] = $value;

        $this->_setModified('created_date');
    }

    /**
     * Get the Build model for this BuildError by Id.
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
