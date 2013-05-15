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
	/**
	* @var array
	*/
	public static $sleepable = array();

	/**
	* @var string
	*/
	protected $_tableName = 'user';

	/**
	* @var string
	*/
	protected $_modelName = 'User';

	/**
	* @var array
	*/
	protected $_data = array(
		'id' => null,
		'email' => null,
		'hash' => null,
		'is_admin' => null,
		'name' => null,
		);

	/**
	* @var array
	*/
	protected $_getters = array(
		'id' => 'getId',
		'email' => 'getEmail',
		'hash' => 'getHash',
		'is_admin' => 'getIsAdmin',
		'name' => 'getName',
		);

	/**
	* @var array
	*/
	protected $_setters = array(
		'id' => 'setId',
		'email' => 'setEmail',
		'hash' => 'setHash',
		'is_admin' => 'setIsAdmin',
		'name' => 'setName',
		);

	/**
	* @var array
	*/
	public $columns = array(
		'id' => array(
			'type' => 'int',
			'length' => '11',
			'primary_key' => true,
			'auto_increment' => true,
			),
		'email' => array(
			'type' => 'varchar',
			'length' => '250',
			),
		'hash' => array(
			'type' => 'varchar',
			'length' => '250',
			),
		'is_admin' => array(
			'type' => 'tinyint',
			'length' => '1',
			),
		'name' => array(
			'type' => 'varchar',
			'length' => '250',
			),
		);

	/**
	* @var array
	*/
	public $indexes = array(
			'PRIMARY' => array('unique' => true, 'columns' => 'id'),
			'idx_email' => array('unique' => true, 'columns' => 'email'),
		);

	/**
	* @var array
	*/
	public $foreignKeys = array(
		);


	/**
	* Get the value of Id / id.
	*
	* @return int
	*/
	public function getId()
	{
		$rtn    = $this->_data['id'];

		
		return $rtn;
	}

	/**
	* Get the value of Email / email.
	*
	* @return string
	*/
	public function getEmail()
	{
		$rtn    = $this->_data['email'];

		
		return $rtn;
	}

	/**
	* Get the value of Hash / hash.
	*
	* @return string
	*/
	public function getHash()
	{
		$rtn    = $this->_data['hash'];

		
		return $rtn;
	}

	/**
	* Get the value of IsAdmin / is_admin.
	*
	* @return int
	*/
	public function getIsAdmin()
	{
		$rtn    = $this->_data['is_admin'];

		
		return $rtn;
	}

	/**
	* Get the value of Name / name.
	*
	* @return string
	*/
	public function getName()
	{
		$rtn    = $this->_data['name'];

		
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
		if($this->_data['id'] === $value)
		{
			return;
		}

		$this->_data['id'] = $value;

		$this->_setModified('id');
	}

	/**
	* Set the value of Email / email.
	*
	* Must not be null.
	* @param $value string
	*/	
	public function setEmail($value)
	{
		$this->_validateNotNull('Email', $value);
		$this->_validateString('Email', $value);
		if($this->_data['email'] === $value)
		{
			return;
		}

		$this->_data['email'] = $value;

		$this->_setModified('email');
	}

	/**
	* Set the value of Hash / hash.
	*
	* Must not be null.
	* @param $value string
	*/	
	public function setHash($value)
	{
		$this->_validateNotNull('Hash', $value);
		$this->_validateString('Hash', $value);
		if($this->_data['hash'] === $value)
		{
			return;
		}

		$this->_data['hash'] = $value;

		$this->_setModified('hash');
	}

	/**
	* Set the value of IsAdmin / is_admin.
	*
	* Must not be null.
	* @param $value int
	*/	
	public function setIsAdmin($value)
	{
		$this->_validateNotNull('IsAdmin', $value);
		$this->_validateInt('IsAdmin', $value);
		if($this->_data['is_admin'] === $value)
		{
			return;
		}

		$this->_data['is_admin'] = $value;

		$this->_setModified('is_admin');
	}

	/**
	* Set the value of Name / name.
	*
	* Must not be null.
	* @param $value string
	*/	
	public function setName($value)
	{
		$this->_validateNotNull('Name', $value);
		$this->_validateString('Name', $value);
		if($this->_data['name'] === $value)
		{
			return;
		}

		$this->_data['name'] = $value;

		$this->_setModified('name');
	}

}
