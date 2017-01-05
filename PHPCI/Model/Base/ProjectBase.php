<?php

/**
 * Project base model for table: project
 */

namespace PHPCI\Model\Base;

use DateTime;
use Block8\Database\Query;
use PHPCI\Model;
use PHPCI\Model\Project;
use PHPCI\Store;
use PHPCI\Store\ProjectStore;

/**
 * Project Base Model
 */
abstract class ProjectBase extends Model
{
    protected $table = 'project';
    protected $model = 'Project';
    protected $data = [
        'id' => null,
        'title' => null,
        'reference' => null,
        'branch' => 'master',
        'ssh_private_key' => null,
        'ssh_public_key' => null,
        'type' => null,
        'access_information' => null,
        'last_commit' => null,
        'build_config' => null,
        'allow_public_status' => null,
        'archived' => null,
        'group_id' => 1,
    ];

    protected $getters = [
        'id' => 'getId',
        'title' => 'getTitle',
        'reference' => 'getReference',
        'branch' => 'getBranch',
        'ssh_private_key' => 'getSshPrivateKey',
        'ssh_public_key' => 'getSshPublicKey',
        'type' => 'getType',
        'access_information' => 'getAccessInformation',
        'last_commit' => 'getLastCommit',
        'build_config' => 'getBuildConfig',
        'allow_public_status' => 'getAllowPublicStatus',
        'archived' => 'getArchived',
        'group_id' => 'getGroupId',
        'Group' => 'getGroup',
    ];

    protected $setters = [
        'id' => 'setId',
        'title' => 'setTitle',
        'reference' => 'setReference',
        'branch' => 'setBranch',
        'ssh_private_key' => 'setSshPrivateKey',
        'ssh_public_key' => 'setSshPublicKey',
        'type' => 'setType',
        'access_information' => 'setAccessInformation',
        'last_commit' => 'setLastCommit',
        'build_config' => 'setBuildConfig',
        'allow_public_status' => 'setAllowPublicStatus',
        'archived' => 'setArchived',
        'group_id' => 'setGroupId',
        'Group' => 'setGroup',
    ];

    /**
     * Return the database store for this model.
     * @return ProjectStore
     */
    public static function Store() : ProjectStore
    {
        return ProjectStore::load();
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
     * Get the value of Title / title
     * @return string
     */

     public function getTitle() : string
     {
        $rtn = $this->data['title'];

        return $rtn;
     }
    
    /**
     * Get the value of Reference / reference
     * @return string
     */

     public function getReference() : string
     {
        $rtn = $this->data['reference'];

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
     * Get the value of SshPrivateKey / ssh_private_key
     * @return string|null
     */

     public function getSshPrivateKey() 
     {
        $rtn = $this->data['ssh_private_key'];

        return $rtn;
     }
    
    /**
     * Get the value of SshPublicKey / ssh_public_key
     * @return string|null
     */

     public function getSshPublicKey() 
     {
        $rtn = $this->data['ssh_public_key'];

        return $rtn;
     }
    
    /**
     * Get the value of Type / type
     * @return string
     */

     public function getType() : string
     {
        $rtn = $this->data['type'];

        return $rtn;
     }
    
    /**
     * Get the value of AccessInformation / access_information
     * @return string|null
     */

     public function getAccessInformation() 
     {
        $rtn = $this->data['access_information'];

        return $rtn;
     }
    
    /**
     * Get the value of LastCommit / last_commit
     * @return string|null
     */

     public function getLastCommit() 
     {
        $rtn = $this->data['last_commit'];

        return $rtn;
     }
    
    /**
     * Get the value of BuildConfig / build_config
     * @return string|null
     */

     public function getBuildConfig() 
     {
        $rtn = $this->data['build_config'];

        return $rtn;
     }
    
    /**
     * Get the value of AllowPublicStatus / allow_public_status
     * @return int
     */

     public function getAllowPublicStatus() : int
     {
        $rtn = $this->data['allow_public_status'];

        return $rtn;
     }
    
    /**
     * Get the value of Archived / archived
     * @return int
     */

     public function getArchived() : int
     {
        $rtn = $this->data['archived'];

        return $rtn;
     }
    
    /**
     * Get the value of GroupId / group_id
     * @return int
     */

     public function getGroupId() : int
     {
        $rtn = $this->data['group_id'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return Project
     */
    public function setId(int $value) : Project
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of Title / title
     * @param $value string
     * @return Project
     */
    public function setTitle(string $value) : Project
    {

        if ($this->data['title'] !== $value) {
            $this->data['title'] = $value;
            $this->setModified('title');
        }

        return $this;
    }
    
    /**
     * Set the value of Reference / reference
     * @param $value string
     * @return Project
     */
    public function setReference(string $value) : Project
    {

        if ($this->data['reference'] !== $value) {
            $this->data['reference'] = $value;
            $this->setModified('reference');
        }

        return $this;
    }
    
    /**
     * Set the value of Branch / branch
     * @param $value string
     * @return Project
     */
    public function setBranch(string $value) : Project
    {

        if ($this->data['branch'] !== $value) {
            $this->data['branch'] = $value;
            $this->setModified('branch');
        }

        return $this;
    }
    
    /**
     * Set the value of SshPrivateKey / ssh_private_key
     * @param $value string|null
     * @return Project
     */
    public function setSshPrivateKey($value) : Project
    {

        if ($this->data['ssh_private_key'] !== $value) {
            $this->data['ssh_private_key'] = $value;
            $this->setModified('ssh_private_key');
        }

        return $this;
    }
    
    /**
     * Set the value of SshPublicKey / ssh_public_key
     * @param $value string|null
     * @return Project
     */
    public function setSshPublicKey($value) : Project
    {

        if ($this->data['ssh_public_key'] !== $value) {
            $this->data['ssh_public_key'] = $value;
            $this->setModified('ssh_public_key');
        }

        return $this;
    }
    
    /**
     * Set the value of Type / type
     * @param $value string
     * @return Project
     */
    public function setType(string $value) : Project
    {

        if ($this->data['type'] !== $value) {
            $this->data['type'] = $value;
            $this->setModified('type');
        }

        return $this;
    }
    
    /**
     * Set the value of AccessInformation / access_information
     * @param $value string|null
     * @return Project
     */
    public function setAccessInformation($value) : Project
    {

        if ($this->data['access_information'] !== $value) {
            $this->data['access_information'] = $value;
            $this->setModified('access_information');
        }

        return $this;
    }
    
    /**
     * Set the value of LastCommit / last_commit
     * @param $value string|null
     * @return Project
     */
    public function setLastCommit($value) : Project
    {

        if ($this->data['last_commit'] !== $value) {
            $this->data['last_commit'] = $value;
            $this->setModified('last_commit');
        }

        return $this;
    }
    
    /**
     * Set the value of BuildConfig / build_config
     * @param $value string|null
     * @return Project
     */
    public function setBuildConfig($value) : Project
    {

        if ($this->data['build_config'] !== $value) {
            $this->data['build_config'] = $value;
            $this->setModified('build_config');
        }

        return $this;
    }
    
    /**
     * Set the value of AllowPublicStatus / allow_public_status
     * @param $value int
     * @return Project
     */
    public function setAllowPublicStatus(int $value) : Project
    {

        if ($this->data['allow_public_status'] !== $value) {
            $this->data['allow_public_status'] = $value;
            $this->setModified('allow_public_status');
        }

        return $this;
    }
    
    /**
     * Set the value of Archived / archived
     * @param $value int
     * @return Project
     */
    public function setArchived(int $value) : Project
    {

        if ($this->data['archived'] !== $value) {
            $this->data['archived'] = $value;
            $this->setModified('archived');
        }

        return $this;
    }
    
    /**
     * Set the value of GroupId / group_id
     * @param $value int
     * @return Project
     */
    public function setGroupId(int $value) : Project
    {

        // As this column is a foreign key, empty values should be considered null.
        if (empty($value)) {
            $value = null;
        }


        if ($this->data['group_id'] !== $value) {
            $this->data['group_id'] = $value;
            $this->setModified('group_id');
        }

        return $this;
    }
    
    
    /**
     * Get the ProjectGroup model for this  by Id.
     *
     * @uses \PHPCI\Store\ProjectGroupStore::getById()
     * @uses \PHPCI\Model\ProjectGroup
     * @return \PHPCI\Model\ProjectGroup
     */
    public function getGroup()
    {
        $key = $this->getGroupId();

        if (empty($key)) {
           return null;
        }

        return Store::get('ProjectGroup')->getById($key);
    }

    /**
     * Set Group - Accepts an ID, an array representing a ProjectGroup or a ProjectGroup model.
     * @throws \Exception
     * @param $value mixed
     */
    public function setGroup($value)
    {
        // Is this a scalar value representing the ID of this foreign key?
        if (is_scalar($value)) {
            return $this->setGroupId($value);
        }

        // Is this an instance of Group?
        if (is_object($value) && $value instanceof \PHPCI\Model\ProjectGroup) {
            return $this->setGroupObject($value);
        }

        // Is this an array representing a ProjectGroup item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setGroupId($value['id']);
        }

        // None of the above? That's a problem!
        throw new \Exception('Invalid value for Group.');
    }

    /**
     * Set Group - Accepts a ProjectGroup model.
     *
     * @param $value \PHPCI\Model\ProjectGroup
     */
    public function setGroupObject(\PHPCI\Model\ProjectGroup $value)
    {
        return $this->setGroupId($value->getId());
    }


    public function Builds() : Query
    {
        return Store::get('Build')->where('project_id', $this->data['id']);
    }

    public function BuildMetas() : Query
    {
        return Store::get('BuildMeta')->where('project_id', $this->data['id']);
    }
}
