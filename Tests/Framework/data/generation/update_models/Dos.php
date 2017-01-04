<?php

namespace Update\Model\Base;
use PHPCI\Framework\Model;

class Dos extends Model
{
	protected $_tableName = 'dos';

	public $columns         = array(
		'id'            =>  array('type' => 'int', 'primary_key' => true, 'auto_increment' => true),
		'field_varchar' =>  array('type' => 'varchar', 'length' => '250', 'default' => 'Hello World'),
		'field_datetime'=>  array('type'  => 'datetime'),
		'field_int'     =>  array('type'  => 'int'),
	);

	public $indexes         = array(
		'PRIMARY'       => array('unique' => true, 'columns' => 'id'),
		'idx_test_1'    => array('unique' => false, 'columns' => 'field_int'),
		'idx_test_2'    => array('columns' => 'field_datetime'),
	);
	public $foreignKeys     = array(
	);
}