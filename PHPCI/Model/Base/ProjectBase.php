<?php

/**
 * Project base model for table: project
 */

namespace PHPCI\Model\Base;
use b8\Model;

/**
 * Project Base Model
 */
class ProjectBase extends Model
{
	public static $sleepable= array();
	protected $_tableName   = 'project';
	protected $_modelName   = 'Project';
	protected $_data        = array(
		'id'    =>    null,
		'title'    =>    null,
		'reference'    =>    null,
		'git_key'    =>    null,
		'type'    =>    null,
	);
	protected $_getters     = array(
		'id'    =>    'getId',
		'title'    =>    'getTitle',
		'reference'    =>    'getReference',
		'git_key'    =>    'getGitKey',
		'type'    =>    'getType',
	);

	protected $_setters     = array(
		'id'    =>    'setId',
		'title'    =>    'setTitle',
		'reference'    =>    'setReference',
		'git_key'    =>    'setGitKey',
		'type'    =>    'setType',
	);

	public $columns         = array(
		'id'    =>    array(
			'type' => 'int',
			'length' => '11',

			'primary_key' => true,
			'auto_increment' => true,

		),
		'title'    =>    array(
			'type' => 'varchar',
			'length' => '250',
		),
		'reference'    =>    array(
			'type' => 'varchar',
			'length' => '250',
		),
		'git_key'    =>    array(
			'type' => 'text',
			'length' => '',
		),
		'type'    =>    array(
			'type' => 'varchar',
			'length' => '50',
		),
	);

	public $indexes         = array(
		'PRIMARY'    =>    array('unique' => true, 'columns' => 'id'),
	);

	public $foreignKeys     = array(
	);

	public function getId()
	{
		$rtn    = $this->_data['id'];


		return $rtn;
	}

	public function getTitle()
	{
		$rtn    = $this->_data['title'];


		return $rtn;
	}

	public function getReference()
	{
		$rtn    = $this->_data['reference'];


		return $rtn;
	}

	public function getGitKey()
	{
		$rtn    = $this->_data['git_key'];


		return $rtn;
	}

	public function getType()
	{
		$rtn    = $this->_data['type'];


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

	public function setTitle($value)
	{
		$this->_validateNotNull('Title', $value);
		$this->_validateString('Title', $value);
		if($this->_data['title'] === $value)
		{
			return;
		}

		$this->_data['title'] = $value;

		$this->_setModified('title');
	}

	public function setReference($value)
	{
		$this->_validateNotNull('Reference', $value);
		$this->_validateString('Reference', $value);
		if($this->_data['reference'] === $value)
		{
			return;
		}

		$this->_data['reference'] = $value;

		$this->_setModified('reference');
	}

	public function setGitKey($value)
	{
		$this->_validateNotNull('GitKey', $value);
		$this->_validateString('GitKey', $value);
		if($this->_data['git_key'] === $value)
		{
			return;
		}

		$this->_data['git_key'] = $value;

		$this->_setModified('git_key');
	}

	public function setType($value)
	{
		$this->_validateNotNull('Type', $value);
		$this->_validateString('Type', $value);
		if($this->_data['type'] === $value)
		{
			return;
		}

		$this->_data['type'] = $value;

		$this->_setModified('type');
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
		return \b8\Store\Factory::getStore('Build')->getByProjectId($this->getId());
	}

}
