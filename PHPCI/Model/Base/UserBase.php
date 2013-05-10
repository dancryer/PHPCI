<?php

/**
 * User base model for table: user
 */

namespace PHPCI\Model\Base;
use b8\Model;

/**
 * User Base Model
 */
class UserBase extends Model
{
	public static $sleepable= array();
	protected $_tableName   = 'user';
	protected $_modelName   = 'User';
	protected $_data        = array(
					'id'    =>    null,
					'email'    =>    null,
					'hash'    =>    null,
					'is_admin'    =>    null,
					'name'    =>    null,
                                  );
	protected $_getters     = array(
					'id'    =>    'getId',
					'email'    =>    'getEmail',
					'hash'    =>    'getHash',
					'is_admin'    =>    'getIsAdmin',
					'name'    =>    'getName',


							);

	protected $_setters     = array(
					'id'    =>    'setId',
					'email'    =>    'setEmail',
					'hash'    =>    'setHash',
					'is_admin'    =>    'setIsAdmin',
					'name'    =>    'setName',

                                 );
	public $columns         = array(
					'id'    =>    array(
													'type' => 'int',
													'length' => '11',

													'primary_key' => true,
													'auto_increment' => true,

												),
					'email'    =>    array(
													'type' => 'varchar',
													'length' => '250',




												),
					'hash'    =>    array(
													'type' => 'varchar',
													'length' => '250',




												),
					'is_admin'    =>    array(
													'type' => 'tinyint',
													'length' => '1',




												),
					'name'    =>    array(
													'type' => 'varchar',
													'length' => '250',




												),
                                  );
	public $indexes         = array(
					'PRIMARY'    =>    array('unique' => true, 'columns' => 'id'),
					'idx_email'    =>    array('unique' => true, 'columns' => 'email'),
                                  );
	public $foreignKeys     = array(
                                  );



	public function getId()
	{
		$rtn    = $this->_data['id'];

		
		return $rtn;
	}

	public function getEmail()
	{
		$rtn    = $this->_data['email'];

		
		return $rtn;
	}

	public function getHash()
	{
		$rtn    = $this->_data['hash'];

		
		return $rtn;
	}

	public function getIsAdmin()
	{
		$rtn    = $this->_data['is_admin'];

		
		return $rtn;
	}

	public function getName()
	{
		$rtn    = $this->_data['name'];

		
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

	public function setEmail($value)
	{
		$this->_validateNotNull('Email', $value);
		$this->_validateString('Email', $value);
		if($this->_data['email'] == $value)
		{
			return;
		}

		$this->_data['email'] = $value;

		$this->_setModified('email');
	}

	public function setHash($value)
	{
		$this->_validateNotNull('Hash', $value);
		$this->_validateString('Hash', $value);
		if($this->_data['hash'] == $value)
		{
			return;
		}

		$this->_data['hash'] = $value;

		$this->_setModified('hash');
	}

	public function setIsAdmin($value)
	{
		$this->_validateNotNull('IsAdmin', $value);
		$this->_validateInt('IsAdmin', $value);
		if($this->_data['is_admin'] == $value)
		{
			return;
		}

		$this->_data['is_admin'] = $value;

		$this->_setModified('is_admin');
	}

	public function setName($value)
	{
		$this->_validateNotNull('Name', $value);
		$this->_validateString('Name', $value);
		if($this->_data['name'] == $value)
		{
			return;
		}

		$this->_data['name'] = $value;

		$this->_setModified('name');
	}





}
