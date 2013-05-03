<?php

/**
 * Build base model for table: build
 */

namespace PHPCI\Model\Base;
use b8\Model;

/**
 * Build Base Model
 */
class BuildBase extends Model
{
	public static $sleepable= array();
	protected $_tableName   = 'build';
	protected $_modelName   = 'Build';
	protected $_data        = array(
					'id'    =>    null,
					'project_id'    =>    null,
					'commit_id'    =>    null,
					'status'    =>    null,
					'log'    =>    null,
					'branch'    =>    null,
                                  );
	protected $_getters     = array(
					'id'    =>    'getId',
					'project_id'    =>    'getProjectId',
					'commit_id'    =>    'getCommitId',
					'status'    =>    'getStatus',
					'log'    =>    'getLog',
					'branch'    =>    'getBranch',

					'Project'  => 'getProject',

							);

	protected $_setters     = array(
					'id'    =>    'setId',
					'project_id'    =>    'setProjectId',
					'commit_id'    =>    'setCommitId',
					'status'    =>    'setStatus',
					'log'    =>    'setLog',
					'branch'    =>    'setBranch',

	'Project'  => 'setProject',
                                 );
	public $columns         = array(
					'id'    =>    array(
													'type' => 'int',
													'length' => '11',

													'primary_key' => true,
													'auto_increment' => true,

												),
					'project_id'    =>    array(
													'type' => 'int',
													'length' => '11',




												),
					'commit_id'    =>    array(
													'type' => 'varchar',
													'length' => '50',




												),
					'status'    =>    array(
													'type' => 'tinyint',
													'length' => '4',




												),
					'log'    =>    array(
													'type' => 'text',
													'length' => '',
													'nullable' => true,



												),
					'branch'    =>    array(
													'type' => 'varchar',
													'length' => '50',




												),
                                  );
	public $indexes         = array(
					'PRIMARY'    =>    array('unique' => true, 'columns' => 'id'),
					'project_id'    =>    array('columns' => 'project_id'),
					'idx_status'    =>    array('columns' => 'status'),
                                  );
	public $foreignKeys     = array(
					'build_ibfk_1'    =>    array('local_col' => 'project_id', 'update' => 'CASCADE', 'delete' => 'CASCADE', 'table' => 'project', 'col' => 'id'),
                                  );



	public function getId()
	{
		$rtn    = $this->_data['id'];

		
		return $rtn;
	}

	public function getProjectId()
	{
		$rtn    = $this->_data['project_id'];

		
		return $rtn;
	}

	public function getCommitId()
	{
		$rtn    = $this->_data['commit_id'];

		
		return $rtn;
	}

	public function getStatus()
	{
		$rtn    = $this->_data['status'];

		
		return $rtn;
	}

	public function getLog()
	{
		$rtn    = $this->_data['log'];

		
		return $rtn;
	}

	public function getBranch()
	{
		$rtn    = $this->_data['branch'];

		
		return $rtn;
	}



	public function setId($value)
	{
		$this->_validateNotNull('Id', $value);
		$this->_validateInt('Id', $value);
		if($this->_data['id'] == $value)
		{
			return;
		}

		$this->_data['id'] = $value;

		$this->_setModified('id');
	}

	public function setProjectId($value)
	{
		$this->_validateNotNull('ProjectId', $value);
		$this->_validateInt('ProjectId', $value);
		if($this->_data['project_id'] == $value)
		{
			return;
		}

		$this->_data['project_id'] = $value;

		$this->_setModified('project_id');
	}

	public function setCommitId($value)
	{
		$this->_validateNotNull('CommitId', $value);
		$this->_validateString('CommitId', $value);
		if($this->_data['commit_id'] == $value)
		{
			return;
		}

		$this->_data['commit_id'] = $value;

		$this->_setModified('commit_id');
	}

	public function setStatus($value)
	{
		$this->_validateNotNull('Status', $value);
		$this->_validateInt('Status', $value);
		if($this->_data['status'] == $value)
		{
			return;
		}

		$this->_data['status'] = $value;

		$this->_setModified('status');
	}

	public function setLog($value)
	{

		$this->_validateString('Log', $value);
		if($this->_data['log'] == $value)
		{
			return;
		}

		$this->_data['log'] = $value;

		$this->_setModified('log');
	}

	public function setBranch($value)
	{
		$this->_validateNotNull('Branch', $value);
		$this->_validateString('Branch', $value);
		if($this->_data['branch'] == $value)
		{
			return;
		}

		$this->_data['branch'] = $value;

		$this->_setModified('branch');
	}



	/**
	 * Get the Project model for this Build by Id.
	 *
	 * @uses \PHPCI\Store\ProjectStore::getById()
	 * @uses \PHPCI\Model\Project
	 * @return \PHPCI\Model\Project
	 */
	public function getProject()
	{
		$key = $this->getProjectId();

		if(empty($key))
		{
			return null;
		}

		return \b8\Store\Factory::getStore('Project')->getById($key);
	}

	public function setProject($value)
	{
		// Is this an instance of Project?
		if($value instanceof \PHPCI\Model\Project)
		{
			return $this->setProjectObject($value);
		}

		// Is this an array representing a Project item?
		if(is_array($value) && !empty($value['id']))
		{
			return $this->setProjectId($value['id']);
		}

		// Is this a scalar value representing the ID of this foreign key?
		return $this->setProjectId($value);
	}

	public function setProjectObject(\PHPCI\Model\Project $value)
	{
		return $this->setProjectId($value->getId());
	}



}
