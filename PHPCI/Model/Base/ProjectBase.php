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
        'id'                 => null,
        'title'              => null,
        'reference'          => null,
        'git_key'            => null,
        'build_config'       => null,
        'type'               => null,
        'token'              => null,
        'access_information' => null,
        'last_commit'        => null,
    );

    /**
     * @var array
     */
    protected $getters = array(
        // Direct property getters:
        'id'                 => 'getId',
        'title'              => 'getTitle',
        'reference'          => 'getReference',
        'git_key'            => 'getGitKey',
        'build_config'       => 'getBuildConfig',
        'type'               => 'getType',
        'token'              => 'getToken',
        'access_information' => 'getAccessInformation',
        'last_commit'        => 'getLastCommit',
        // Foreign key getters:
    );

    /**
     * @var array
     */
    protected $setters = array(
        // Direct property setters:
        'id'                 => 'setId',
        'title'              => 'setTitle',
        'reference'          => 'setReference',
        'git_key'            => 'setGitKey',
        'build_config'       => 'setBuildConfig',
        'type'               => 'setType',
        'token'              => 'setToken',
        'access_information' => 'setAccessInformation',
        'last_commit'        => 'setLastCommit',
        // Foreign key setters:
    );

    /**
     * @var array
     */
    public $columns = array(
        'id'                 => array(
            'type'           => 'int',
            'length'         => 11,
            'primary_key'    => true,
            'auto_increment' => true,
            'default'        => null,
        ),
        'title'              => array(
            'type'    => 'varchar',
            'length'  => 250,
            'default' => null,
        ),
        'reference'          => array(
            'type'    => 'varchar',
            'length'  => 250,
            'default' => null,
        ),
        'git_key'            => array(
            'type'     => 'text',
            'nullable' => true,
            'default'  => null,
        ),
        'build_config'       => array(
            'type'     => 'text',
            'nullable' => true,
            'default'  => null,
        ),
        'type'               => array(
            'type'    => 'varchar',
            'length'  => 50,
            'default' => 1,
        ),
        'token'              => array(
            'type'     => 'varchar',
            'length'   => 50,
            'nullable' => true,
            'default'  => null,
        ),
        'access_information' => array(
            'type'     => 'varchar',
            'length'   => 250,
            'nullable' => true,
            'default'  => null,
        ),
        'last_commit'        => array(
            'type'     => 'varchar',
            'length'   => 250,
            'nullable' => true,
            'default'  => null,
        ),
    );

    /**
     * @var array
     */
    public $indexes = array(
        'PRIMARY'           => array('unique' => true, 'columns' => 'id'),
        'idx_project_title' => array('columns' => 'title'),
    );

    /**
     * @var array
     */
    public $foreignKeys = array();

    /**
     * Get the value of Id / id.
     *
     * @return int
     */
    public function getId()
    {
        $rtn = $this->data['id'];
        return $rtn;
    }

    /**
     * Get the value of Title / title.
     *
     * @return string
     */
    public function getTitle()
    {
        $rtn = $this->data['title'];
        return $rtn;
    }

    /**
     * Get the value of Reference / reference.
     *
     * @return string
     */
    public function getReference()
    {
        $rtn = $this->data['reference'];
        return $rtn;
    }

    /**
     * Get the value of GitKey / git_key.
     *
     * @return string
     */
    public function getGitKey()
    {
        $rtn = $this->data['git_key'];
        return $rtn;
    }

    /**
     * Get the value of BuildConfig / build_config.
     *
     * @return string
     */
    public function getBuildConfig()
    {
        $rtn = $this->data['build_config'];
        return $rtn;
    }

    /**
     * Get the value of Type / type.
     *
     * @return string
     */
    public function getType()
    {
        $rtn = $this->data['type'];
        return $rtn;
    }

    /**
     * Get the value of Token / token.
     *
     * @return string
     */
    public function getToken()
    {
        $rtn = $this->data['token'];
        return $rtn;
    }

    /**
     * Get the value of AccessInformation / access_information.
     *
     * @return string
     */
    public function getAccessInformation()
    {
        $rtn = $this->data['access_information'];
        return $rtn;
    }

    /**
     * Get the value of LastCommit / last_commit.
     *
     * @return string
     */
    public function getLastCommit()
    {
        $rtn = $this->data['last_commit'];
        return $rtn;
    }

    /**
     * Set the value of Id / id.
     *
     * Must not be null.
     *
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
     *
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
     *
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
     * Set the value of GitKey / git_key.
     *
     * @param $value string
     */
    public function setGitKey($value)
    {
        $this->_validateString('GitKey', $value);

        if ($this->data['git_key'] === $value) {
            return;
        }
        $this->data['git_key'] = $value;
        $this->_setModified('git_key');
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
     * Set the value of Type / type.
     *
     * Must not be null.
     *
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
     * Set the value of Token / token.
     *
     * @param $value string
     */
    public function setToken($value)
    {
        $this->_validateString('Token', $value);

        if ($this->data['token'] === $value) {
            return;
        }
        $this->data['token'] = $value;
        $this->_setModified('token');
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

    public static function getByPrimaryKey($value, $useConnection = 'read')
    {
        return Factory::getStore('Project', 'PHPCI')->getByPrimaryKey($value, $useConnection);
    }

    public static function getById($value, $useConnection = 'read')
    {
        return Factory::getStore('Project', 'PHPCI')->getById($value, $useConnection);
    }

    public static function getByTitle($value, $limit = null, $useConnection = 'read')
    {
        return Factory::getStore('Project', 'PHPCI')->getByTitle($value, $limit, $useConnection);
    }
}
