<?php

/**
 * BuildError base model for table: build_error
 */

namespace PHPCI\Model\Base;

use DateTime;
use Block8\Database\Query;
use PHPCI\Model;
use PHPCI\Model\BuildError;
use PHPCI\Store;
use PHPCI\Store\BuildErrorStore;

/**
 * BuildError Base Model
 */
abstract class BuildErrorBase extends Model
{
    protected $table = 'build_error';
    protected $model = 'BuildError';
    protected $data = [
        'id' => null,
        'build_id' => null,
        'plugin' => null,
        'file' => null,
        'line_start' => null,
        'line_end' => null,
        'severity' => null,
        'message' => null,
        'created_date' => null,
    ];

    protected $getters = [
        'id' => 'getId',
        'build_id' => 'getBuildId',
        'plugin' => 'getPlugin',
        'file' => 'getFile',
        'line_start' => 'getLineStart',
        'line_end' => 'getLineEnd',
        'severity' => 'getSeverity',
        'message' => 'getMessage',
        'created_date' => 'getCreatedDate',
        'Build' => 'getBuild',
    ];

    protected $setters = [
        'id' => 'setId',
        'build_id' => 'setBuildId',
        'plugin' => 'setPlugin',
        'file' => 'setFile',
        'line_start' => 'setLineStart',
        'line_end' => 'setLineEnd',
        'severity' => 'setSeverity',
        'message' => 'setMessage',
        'created_date' => 'setCreatedDate',
        'Build' => 'setBuild',
    ];

    /**
     * Return the database store for this model.
     * @return BuildErrorStore
     */
    public static function Store() : BuildErrorStore
    {
        return BuildErrorStore::load();
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
     * Get the value of BuildId / build_id
     * @return int
     */

     public function getBuildId() : int
     {
        $rtn = $this->data['build_id'];

        return $rtn;
     }
    
    /**
     * Get the value of Plugin / plugin
     * @return string
     */

     public function getPlugin() : string
     {
        $rtn = $this->data['plugin'];

        return $rtn;
     }
    
    /**
     * Get the value of File / file
     * @return string|null
     */

     public function getFile() 
     {
        $rtn = $this->data['file'];

        return $rtn;
     }
    
    /**
     * Get the value of LineStart / line_start
     * @return int|null
     */

     public function getLineStart() 
     {
        $rtn = $this->data['line_start'];

        return $rtn;
     }
    
    /**
     * Get the value of LineEnd / line_end
     * @return int|null
     */

     public function getLineEnd() 
     {
        $rtn = $this->data['line_end'];

        return $rtn;
     }
    
    /**
     * Get the value of Severity / severity
     * @return int
     */

     public function getSeverity() : int
     {
        $rtn = $this->data['severity'];

        return $rtn;
     }
    
    /**
     * Get the value of Message / message
     * @return string
     */

     public function getMessage() : string
     {
        $rtn = $this->data['message'];

        return $rtn;
     }
    
    /**
     * Get the value of CreatedDate / created_date
     * @return DateTime
     */

     public function getCreatedDate() : DateTime
     {
        $rtn = $this->data['created_date'];

        if (!empty($rtn)) {
            $rtn = new DateTime($rtn);
        }

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return BuildError
     */
    public function setId(int $value) : BuildError
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of BuildId / build_id
     * @param $value int
     * @return BuildError
     */
    public function setBuildId(int $value) : BuildError
    {

        // As this column is a foreign key, empty values should be considered null.
        if (empty($value)) {
            $value = null;
        }


        if ($this->data['build_id'] !== $value) {
            $this->data['build_id'] = $value;
            $this->setModified('build_id');
        }

        return $this;
    }
    
    /**
     * Set the value of Plugin / plugin
     * @param $value string
     * @return BuildError
     */
    public function setPlugin(string $value) : BuildError
    {

        if ($this->data['plugin'] !== $value) {
            $this->data['plugin'] = $value;
            $this->setModified('plugin');
        }

        return $this;
    }
    
    /**
     * Set the value of File / file
     * @param $value string|null
     * @return BuildError
     */
    public function setFile($value) : BuildError
    {

        if ($this->data['file'] !== $value) {
            $this->data['file'] = $value;
            $this->setModified('file');
        }

        return $this;
    }
    
    /**
     * Set the value of LineStart / line_start
     * @param $value int|null
     * @return BuildError
     */
    public function setLineStart($value) : BuildError
    {

        if ($this->data['line_start'] !== $value) {
            $this->data['line_start'] = $value;
            $this->setModified('line_start');
        }

        return $this;
    }
    
    /**
     * Set the value of LineEnd / line_end
     * @param $value int|null
     * @return BuildError
     */
    public function setLineEnd($value) : BuildError
    {

        if ($this->data['line_end'] !== $value) {
            $this->data['line_end'] = $value;
            $this->setModified('line_end');
        }

        return $this;
    }
    
    /**
     * Set the value of Severity / severity
     * @param $value int
     * @return BuildError
     */
    public function setSeverity(int $value) : BuildError
    {

        if ($this->data['severity'] !== $value) {
            $this->data['severity'] = $value;
            $this->setModified('severity');
        }

        return $this;
    }
    
    /**
     * Set the value of Message / message
     * @param $value string
     * @return BuildError
     */
    public function setMessage(string $value) : BuildError
    {

        if ($this->data['message'] !== $value) {
            $this->data['message'] = $value;
            $this->setModified('message');
        }

        return $this;
    }
    
    /**
     * Set the value of CreatedDate / created_date
     * @param $value DateTime
     * @return BuildError
     */
    public function setCreatedDate($value) : BuildError
    {
        $this->validateDate('CreatedDate', $value);

        if ($this->data['created_date'] !== $value) {
            $this->data['created_date'] = $value;
            $this->setModified('created_date');
        }

        return $this;
    }
    
    
    /**
     * Get the Build model for this  by Id.
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

        return Store::get('Build')->getById($key);
    }

    /**
     * Set Build - Accepts an ID, an array representing a Build or a Build model.
     * @throws \Exception
     * @param $value mixed
     */
    public function setBuild($value)
    {
        // Is this a scalar value representing the ID of this foreign key?
        if (is_scalar($value)) {
            return $this->setBuildId($value);
        }

        // Is this an instance of Build?
        if (is_object($value) && $value instanceof \PHPCI\Model\Build) {
            return $this->setBuildObject($value);
        }

        // Is this an array representing a Build item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setBuildId($value['id']);
        }

        // None of the above? That's a problem!
        throw new \Exception('Invalid value for Build.');
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
