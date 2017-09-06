<?php

/**
 * BuildMeta base model for table: build_meta
 */

namespace PHPCI\Model\Base;

use DateTime;
use Block8\Database\Query;
use PHPCI\Model;
use PHPCI\Model\BuildMeta;
use PHPCI\Store;
use PHPCI\Store\BuildMetaStore;

/**
 * BuildMeta Base Model
 */
abstract class BuildMetaBase extends Model
{
    protected $table = 'build_meta';
    protected $model = 'BuildMeta';
    protected $data = [
        'id' => null,
        'project_id' => null,
        'build_id' => null,
        'meta_key' => null,
        'meta_value' => null,
    ];

    protected $getters = [
        'id' => 'getId',
        'project_id' => 'getProjectId',
        'build_id' => 'getBuildId',
        'meta_key' => 'getMetaKey',
        'meta_value' => 'getMetaValue',
        'Build' => 'getBuild',
        'Project' => 'getProject',
    ];

    protected $setters = [
        'id' => 'setId',
        'project_id' => 'setProjectId',
        'build_id' => 'setBuildId',
        'meta_key' => 'setMetaKey',
        'meta_value' => 'setMetaValue',
        'Build' => 'setBuild',
        'Project' => 'setProject',
    ];

    /**
     * Return the database store for this model.
     * @return BuildMetaStore
     */
    public static function Store() : BuildMetaStore
    {
        return BuildMetaStore::load();
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
     * Get the value of ProjectId / project_id
     * @return int
     */

     public function getProjectId() : int
     {
        $rtn = $this->data['project_id'];

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
     * Get the value of MetaKey / meta_key
     * @return string
     */

     public function getMetaKey() : string
     {
        $rtn = $this->data['meta_key'];

        return $rtn;
     }
    
    /**
     * Get the value of MetaValue / meta_value
     * @return string
     */

     public function getMetaValue() : string
     {
        $rtn = $this->data['meta_value'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return BuildMeta
     */
    public function setId(int $value) : BuildMeta
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of ProjectId / project_id
     * @param $value int
     * @return BuildMeta
     */
    public function setProjectId(int $value) : BuildMeta
    {

        // As this column is a foreign key, empty values should be considered null.
        if (empty($value)) {
            $value = null;
        }


        if ($this->data['project_id'] !== $value) {
            $this->data['project_id'] = $value;
            $this->setModified('project_id');
        }

        return $this;
    }
    
    /**
     * Set the value of BuildId / build_id
     * @param $value int
     * @return BuildMeta
     */
    public function setBuildId(int $value) : BuildMeta
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
     * Set the value of MetaKey / meta_key
     * @param $value string
     * @return BuildMeta
     */
    public function setMetaKey(string $value) : BuildMeta
    {

        if ($this->data['meta_key'] !== $value) {
            $this->data['meta_key'] = $value;
            $this->setModified('meta_key');
        }

        return $this;
    }
    
    /**
     * Set the value of MetaValue / meta_value
     * @param $value string
     * @return BuildMeta
     */
    public function setMetaValue(string $value) : BuildMeta
    {

        if ($this->data['meta_value'] !== $value) {
            $this->data['meta_value'] = $value;
            $this->setModified('meta_value');
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

    /**
     * Get the Project model for this  by Id.
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

        return Store::get('Project')->getById($key);
    }

    /**
     * Set Project - Accepts an ID, an array representing a Project or a Project model.
     * @throws \Exception
     * @param $value mixed
     */
    public function setProject($value)
    {
        // Is this a scalar value representing the ID of this foreign key?
        if (is_scalar($value)) {
            return $this->setProjectId($value);
        }

        // Is this an instance of Project?
        if (is_object($value) && $value instanceof \PHPCI\Model\Project) {
            return $this->setProjectObject($value);
        }

        // Is this an array representing a Project item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setProjectId($value['id']);
        }

        // None of the above? That's a problem!
        throw new \Exception('Invalid value for Project.');
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

}
