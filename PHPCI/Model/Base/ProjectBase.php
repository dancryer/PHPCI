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
					'git_url'    =>    null,
					'git_key'    =>    null,
                                  );
	protected $_getters     = array(
					'id'    =>    'getId',
					'title'    =>    'getTitle',
					'git_url'    =>    'getGitUrl',
					'git_key'    =>    'getGitKey',


							);

	protected $_setters     = array(
					'id'    =>    'setId',
					'title'    =>    'setTitle',
					'git_url'    =>    'setGitUrl',
					'git_key'    =>    'setGitKey',

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
					'git_url'    =>    array(
													'type' => 'varchar',
													'length' => '1024',




												),
					'git_key'    =>    array(
													'type' => 'text',
													'length' => '',




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

	public function getGitUrl()
	{
		$rtn    = $this->_data['git_url'];

		
		return $rtn;
	}

	public function getGitKey()
	{
		$rtn    = $this->_data['git_key'];

		
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
		if($this->_data['title'] == $value)
		{
			return;
		}

		$this->_data['title'] = $value;

		$this->_setModified('title');
	}

	public function setGitUrl($value)
	{
		$this->_validateNotNull('GitUrl', $value);
		$this->_validateString('GitUrl', $value);
		if($this->_data['git_url'] == $value)
		{
			return;
		}

		$this->_data['git_url'] = $value;

		$this->_setModified('git_url');
	}

	public function setGitKey($value)
	{
		$this->_validateNotNull('GitKey', $value);
		$this->_validateString('GitKey', $value);
		if($this->_data['git_key'] == $value)
		{
			return;
		}

		$this->_data['git_key'] = $value;

		$this->_setModified('git_key');
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
