<?php

/**
 * Build base model for table: build
 */

namespace PHPCI\Model\Base;

use DateTime;
use Block8\Database\Query;
use PHPCI\Model;
use PHPCI\Model\Build;
use PHPCI\Store;
use PHPCI\Store\BuildStore;

/**
 * Build Base Model
 */
abstract class BuildBase extends Model
{
    protected $table = 'build';
    protected $model = 'Build';
    protected $data = [
        'id' => null,
        'project_id' => null,
        'commit_id' => null,
        'status' => null,
        'log' => null,
        'branch' => 'master',
        'created' => null,
        'started' => null,
        'finished' => null,
        'committer_email' => null,
        'commit_message' => null,
        'extra' => null,
    ];

    protected $getters = [
        'id' => 'getId',
        'project_id' => 'getProjectId',
        'commit_id' => 'getCommitId',
        'status' => 'getStatus',
        'log' => 'getLog',
        'branch' => 'getBranch',
        'created' => 'getCreated',
        'started' => 'getStarted',
        'finished' => 'getFinished',
        'committer_email' => 'getCommitterEmail',
        'commit_message' => 'getCommitMessage',
        'extra' => 'getExtra',
        'Project' => 'getProject',
    ];

    protected $setters = [
        'id' => 'setId',
        'project_id' => 'setProjectId',
        'commit_id' => 'setCommitId',
        'status' => 'setStatus',
        'log' => 'setLog',
        'branch' => 'setBranch',
        'created' => 'setCreated',
        'started' => 'setStarted',
        'finished' => 'setFinished',
        'committer_email' => 'setCommitterEmail',
        'commit_message' => 'setCommitMessage',
        'extra' => 'setExtra',
        'Project' => 'setProject',
    ];

    /**
     * Return the database store for this model.
     * @return BuildStore
     */
    public static function Store() : BuildStore
    {
        return BuildStore::load();
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
     * Get the value of CommitId / commit_id
     * @return string
     */

     public function getCommitId() : string
     {
        $rtn = $this->data['commit_id'];

        return $rtn;
     }
    
    /**
     * Get the value of Status / status
     * @return int
     */

     public function getStatus() : int
     {
        $rtn = $this->data['status'];

        return $rtn;
     }
    
    /**
     * Get the value of Log / log
     * @return string|null
     */

     public function getLog() 
     {
        $rtn = $this->data['log'];

        return $rtn;
     }
    
    /**
     * Get the value of Branch / branch
     * @return string
     */

     public function getBranch() : string
     {
        $rtn = $this->data['branch'];

        return $rtn;
     }
    
    /**
     * Get the value of Created / created
     * @return DateTime|null
     */

     public function getCreated() 
     {
        $rtn = $this->data['created'];

        if (!empty($rtn)) {
            $rtn = new DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of Started / started
     * @return DateTime|null
     */

     public function getStarted() 
     {
        $rtn = $this->data['started'];

        if (!empty($rtn)) {
            $rtn = new DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of Finished / finished
     * @return DateTime|null
     */

     public function getFinished() 
     {
        $rtn = $this->data['finished'];

        if (!empty($rtn)) {
            $rtn = new DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of CommitterEmail / committer_email
     * @return string|null
     */

     public function getCommitterEmail() 
     {
        $rtn = $this->data['committer_email'];

        return $rtn;
     }
    
    /**
     * Get the value of CommitMessage / commit_message
     * @return string|null
     */

     public function getCommitMessage() 
     {
        $rtn = $this->data['commit_message'];

        return $rtn;
     }
    
    /**
     * Get the value of Extra / extra
     * @return string|null
     */

     public function getExtra() 
     {
        $rtn = $this->data['extra'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return Build
     */
    public function setId(int $value) : Build
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
     * @return Build
     */
    public function setProjectId(int $value) : Build
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
     * Set the value of CommitId / commit_id
     * @param $value string
     * @return Build
     */
    public function setCommitId(string $value) : Build
    {

        if ($this->data['commit_id'] !== $value) {
            $this->data['commit_id'] = $value;
            $this->setModified('commit_id');
        }

        return $this;
    }
    
    /**
     * Set the value of Status / status
     * @param $value int
     * @return Build
     */
    public function setStatus(int $value) : Build
    {

        if ($this->data['status'] !== $value) {
            $this->data['status'] = $value;
            $this->setModified('status');
        }

        return $this;
    }
    
    /**
     * Set the value of Log / log
     * @param $value string|null
     * @return Build
     */
    public function setLog($value) : Build
    {

        if ($this->data['log'] !== $value) {
            $this->data['log'] = $value;
            $this->setModified('log');
        }

        return $this;
    }
    
    /**
     * Set the value of Branch / branch
     * @param $value string
     * @return Build
     */
    public function setBranch(string $value) : Build
    {

        if ($this->data['branch'] !== $value) {
            $this->data['branch'] = $value;
            $this->setModified('branch');
        }

        return $this;
    }
    
    /**
     * Set the value of Created / created
     * @param $value DateTime|null
     * @return Build
     */
    public function setCreated($value) : Build
    {
        $this->validateDate('Created', $value);

        if ($this->data['created'] !== $value) {
            $this->data['created'] = $value;
            $this->setModified('created');
        }

        return $this;
    }
    
    /**
     * Set the value of Started / started
     * @param $value DateTime|null
     * @return Build
     */
    public function setStarted($value) : Build
    {
        $this->validateDate('Started', $value);

        if ($this->data['started'] !== $value) {
            $this->data['started'] = $value;
            $this->setModified('started');
        }

        return $this;
    }
    
    /**
     * Set the value of Finished / finished
     * @param $value DateTime|null
     * @return Build
     */
    public function setFinished($value) : Build
    {
        $this->validateDate('Finished', $value);

        if ($this->data['finished'] !== $value) {
            $this->data['finished'] = $value;
            $this->setModified('finished');
        }

        return $this;
    }
    
    /**
     * Set the value of CommitterEmail / committer_email
     * @param $value string|null
     * @return Build
     */
    public function setCommitterEmail($value) : Build
    {

        if ($this->data['committer_email'] !== $value) {
            $this->data['committer_email'] = $value;
            $this->setModified('committer_email');
        }

        return $this;
    }
    
    /**
     * Set the value of CommitMessage / commit_message
     * @param $value string|null
     * @return Build
     */
    public function setCommitMessage($value) : Build
    {

        if ($this->data['commit_message'] !== $value) {
            $this->data['commit_message'] = $value;
            $this->setModified('commit_message');
        }

        return $this;
    }
    
    /**
     * Set the value of Extra / extra
     * @param $value string|null
     * @return Build
     */
    public function setExtra($value) : Build
    {

        if ($this->data['extra'] !== $value) {
            $this->data['extra'] = $value;
            $this->setModified('extra');
        }

        return $this;
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


    public function BuildErrors() : Query
    {
        return Store::get('BuildError')->where('build_id', $this->data['id']);
    }

    public function BuildMetas() : Query
    {
        return Store::get('BuildMeta')->where('build_id', $this->data['id']);
    }
}
