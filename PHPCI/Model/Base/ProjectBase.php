<?php

/**
 * Project base model for table: project
 */

namespace PHPCI\Model\Base;

use PHPCI\Model;
use b8\Store\Factory;

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
        'branch' => null,
        'ssh_private_key' => null,
        'type' => null,
        'access_information' => null,
        'last_commit' => null,
        'build_config' => null,
        'ssh_public_key' => null,
        'allow_public_status' => null,
        'archived' => null,
        'group_id' => null,
    );

    /**
    * @var array
    */
    protected $getters = array(
        // Direct property getters:
        'id' => 'getId',
        'title' => 'getTitle',
        'reference' => 'getReference',
        'branch' => 'getBranch',
        'ssh_private_key' => 'getSshPrivateKey',
        'type' => 'getType',
        'access_information' => 'getAccessInformation',
        'last_commit' => 'getLastCommit',
        'build_config' => 'getBuildConfig',
        'ssh_public_key' => 'getSshPublicKey',
        'allow_public_status' => 'getAllowPublicStatus',
        'archived' => 'getArchived',
        'group_id' => 'getGroupId',

        // Foreign key getters:
        'Group' => 'getGroup',
    );

    /**
    * @var array
    */
    protected $setters = array(
        // Direct property setters:
        'id' => 'setId',
        'title' => 'setTitle',
        'reference' => 'setReference',
        'branch' => 'setBranch',
        'ssh_private_key' => 'setSshPrivateKey',
        'type' => 'setType',
        'access_information' => 'setAccessInformation',
        'last_commit' => 'setLastCommit',
        'build_config' => 'setBuildConfig',
        'ssh_public_key' => 'setSshPublicKey',
        'allow_public_status' => 'setAllowPublicStatus',
        'archived' => 'setArchived',
        'group_id' => 'setGroupId',

        // Foreign key setters:
        'Group' => 'setGroup',
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
            'length' => 250,
            'default' => null,
        ),
        'reference' => array(
            'type' => 'varchar',
            'length' => 250,
            'default' => null,
        ),
        'branch' => array(
            'type' => 'varchar',
            'length' => 50,
            'default' => 'master',
        ),
        'ssh_private_key' => array(
            'type' => 'text',
            'nullable' => true,
            'default' => null,
        ),
        'type' => array(
            'type' => 'varchar',
            'length' => 50,
            'default' => null,
        ),
        'access_information' => array(
            'type' => 'varchar',
            'length' => 250,
            'nullable' => true,
            'default' => null,
        ),
        'last_commit' => array(
            'type' => 'varchar',
            'length' => 250,
            'nullable' => true,
            'default' => null,
        ),
        'build_config' => array(
            'type' => 'text',
            'nullable' => true,
            'default' => null,
        ),
        'ssh_public_key' => array(
            'type' => 'text',
            'nullable' => true,
            'default' => null,
        ),
        'allow_public_status' => array(
            'type' => 'int',
            'length' => 11,
        ),
        'archived' => array(
            'type' => 'tinyint',
            'length' => 1,
            'default' => null,
        ),
        'group_id' => array(
            'type' => 'int',
            'length' => 11,
            'default' => 1,
        ),
    );

    /**
    * @var array
    */
    public $indexes = array(
            'PRIMARY' => array('unique' => true, 'columns' => 'id'),
            'idx_project_title' => array('columns' => 'title'),
            'group_id' => array('columns' => 'group_id'),
    );

    /**
    * @var array
    */
    public $foreignKeys = array(
            'project_ibfk_1' => array(
                'local_col' => 'group_id',
                'update' => 'CASCADE',
                'delete' => '',
                'table' => 'project_group',
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
    * Get the value of Branch / branch.
    *
    * @return string
    */
    public function getBranch()
    {
        $rtn    = $this->data['branch'];

        return $rtn;
    }

    /**
    * Get the value of SshPrivateKey / ssh_private_key.
    *
    * @return string
    */
    public function getSshPrivateKey()
    {
        $rtn    = $this->data['ssh_private_key'];

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
    * Get the value of AccessInformation / access_information.
    *
    * @return string
    */
    public function getAccessInformation()
    {
        $rtn    = $this->data['access_information'];

        return $rtn;
    }

    /**
    * Get the value of LastCommit / last_commit.
    *
    * @return string
    */
    public function getLastCommit()
    {
        $rtn    = $this->data['last_commit'];

        return $rtn;
    }

    /**
    * Get the value of BuildConfig / build_config.
    *
    * @return string
    */
    public function getBuildConfig()
    {
        $rtn    = $this->data['build_config'];

        return $rtn;
    }

    /**
    * Get the value of SshPublicKey / ssh_public_key.
    *
    * @return string
    */
    public function getSshPublicKey()
    {
        $rtn    = $this->data['ssh_public_key'];

        return $rtn;
    }

    /**
    * Get the value of AllowPublicStatus / allow_public_status.
    *
    * @return int
    */
    public function getAllowPublicStatus()
    {
        $rtn    = $this->data['allow_public_status'];

        return $rtn;
    }

    /**
    * Get the value of Archived / archived.
    *
    * @return int
    */
    public function getArchived()
    {
        $rtn    = $this->data['archived'];

        return $rtn;
    }

    /**
    * Get the value of GroupId / group_id.
    *
    * @return int
    */
    public function getGroupId()
    {
        $rtn    = $this->data['group_id'];

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
    * Set the value of Reference / reference.
    *
    * Must not be null.
    * @param $value string
    */
    public function setReference($value)
    {
        $this->_validateNotNull('Reference', $value);
        $this->_validateString('Reference', $value);

        if ($this->data['reference'] === $value) {
            return;
        }

        $this->data['reference'] = $value;

        $this->_setModified('reference');
    }

    /**
    * Set the value of Branch / branch.
    *
    * Must not be null.
    * @param $value string
    */
    public function setBranch($value)
    {
        $this->_validateNotNull('Branch', $value);
        $this->_validateString('Branch', $value);

        if ($this->data['branch'] === $value) {
            return;
        }

        $this->data['branch'] = $value;

        $this->_setModified('branch');
    }

    /**
    * Set the value of SshPrivateKey / ssh_private_key.
    *
    * @param $value string
    */
    public function setSshPrivateKey($value)
    {
        $this->_validateString('SshPrivateKey', $value);

        if ($this->data['ssh_private_key'] === $value) {
            return;
        }

        $this->data['ssh_private_key'] = $value;

        $this->_setModified('ssh_private_key');
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

        if ($this->data['type'] === $value) {
            return;
        }

        $this->data['type'] = $value;

        $this->_setModified('type');
    }

    /**
    * Set the value of AccessInformation / access_information.
    *
    * @param $value string
    */
    public function setAccessInformation($value)
    {
        $this->_validateString('AccessInformation', $value);

        if ($this->data['access_information'] === $value) {
            return;
        }

        $this->data['access_information'] = $value;

        $this->_setModified('access_information');
    }

    /**
    * Set the value of LastCommit / last_commit.
    *
    * @param $value string
    */
    public function setLastCommit($value)
    {
        $this->_validateString('LastCommit', $value);

        if ($this->data['last_commit'] === $value) {
            return;
        }

        $this->data['last_commit'] = $value;

        $this->_setModified('last_commit');
    }

    /**
    * Set the value of BuildConfig / build_config.
    *
    * @param $value string
    */
    public function setBuildConfig($value)
    {
        $this->_validateString('BuildConfig', $value);

        if ($this->data['build_config'] === $value) {
            return;
        }

        $this->data['build_config'] = $value;

        $this->_setModified('build_config');
    }

    /**
    * Set the value of SshPublicKey / ssh_public_key.
    *
    * @param $value string
    */
    public function setSshPublicKey($value)
    {
        $this->_validateString('SshPublicKey', $value);

        if ($this->data['ssh_public_key'] === $value) {
            return;
        }

        $this->data['ssh_public_key'] = $value;

        $this->_setModified('ssh_public_key');
    }

    /**
    * Set the value of AllowPublicStatus / allow_public_status.
    *
    * Must not be null.
    * @param $value int
    */
    public function setAllowPublicStatus($value)
    {
        $this->_validateNotNull('AllowPublicStatus', $value);
        $this->_validateInt('AllowPublicStatus', $value);

        if ($this->data['allow_public_status'] === $value) {
            return;
        }

        $this->data['allow_public_status'] = $value;

        $this->_setModified('allow_public_status');
    }

    /**
    * Set the value of Archived / archived.
    *
    * Must not be null.
    * @param $value int
    */
    public function setArchived($value)
    {
        $this->_validateNotNull('Archived', $value);
        $this->_validateInt('Archived', $value);

        if ($this->data['archived'] === $value) {
            return;
        }

        $this->data['archived'] = $value;

        $this->_setModified('archived');
    }

    /**
    * Set the value of GroupId / group_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setGroupId($value)
    {
        $this->_validateNotNull('GroupId', $value);
        $this->_validateInt('GroupId', $value);

        if ($this->data['group_id'] === $value) {
            return;
        }

        $this->data['group_id'] = $value;

        $this->_setModified('group_id');
    }

    /**
     * Get the ProjectGroup model for this Project by Id.
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

        $cacheKey   = 'Cache.ProjectGroup.' . $key;
        $rtn        = $this->cache->get($cacheKey, null);

        if (empty($rtn)) {
            $rtn    = Factory::getStore('ProjectGroup', 'PHPCI')->getById($key);
            $this->cache->set($cacheKey, $rtn);
        }

        return $rtn;
    }

    /**
    * Set Group - Accepts an ID, an array representing a ProjectGroup or a ProjectGroup model.
    *
    * @param $value mixed
    */
    public function setGroup($value)
    {
        // Is this an instance of ProjectGroup?
        if ($value instanceof \PHPCI\Model\ProjectGroup) {
            return $this->setGroupObject($value);
        }

        // Is this an array representing a ProjectGroup item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setGroupId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setGroupId($value);
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

    /**
     * Get Build models by ProjectId for this Project.
     *
     * @uses \PHPCI\Store\BuildStore::getByProjectId()
     * @uses \PHPCI\Model\Build
     * @return \PHPCI\Model\Build[]
     */
    public function getProjectBuilds()
    {
        return Factory::getStore('Build', 'PHPCI')->getByProjectId($this->getId());
    }

    /**
     * Get BuildMeta models by ProjectId for this Project.
     *
     * @uses \PHPCI\Store\BuildMetaStore::getByProjectId()
     * @uses \PHPCI\Model\BuildMeta
     * @return \PHPCI\Model\BuildMeta[]
     */
    public function getProjectBuildMetas()
    {
        return Factory::getStore('BuildMeta', 'PHPCI')->getByProjectId($this->getId());
    }
}
