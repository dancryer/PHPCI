<?php

namespace Test\Model\Base;
use PHPCI\Framework\Model;

class Uno extends Model
{
	protected $_tableName = 'uno';

	public $columns         = array(
		'id'            =>  array('type' => 'int', 'primary_key' => true, 'auto_increment' => true),
		'field_varchar' =>  array('type' => 'varchar', 'length' => '250'),
		'field_text'    =>  array('type'  => 'text'),
		'field_ltext'   =>  array('type'  => 'longtext'),
		'field_mtext'   =>  array('type'  => 'mediumtext'),
		'field_date'    =>  array('type'  => 'date'),
		'field_datetime'=>  array('type'  => 'datetime'),
		'field_int'     =>  array('type'  => 'int'),
		'field_tinyint' =>  array('type'  => 'tinyint', 'length' => '1'),
		'field_float'   =>  array('type'  => 'float'),
		'field_double'  =>  array('type'  => 'double', 'length' => '15,2'),
	);

	public $indexes         = array(
	);
	public $foreignKeys     = array(
	);
}