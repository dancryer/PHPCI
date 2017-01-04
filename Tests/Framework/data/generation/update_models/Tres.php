<?php

namespace Update\Model\Base;
use PHPCI\Framework\Model;

class Tres extends Model
{
	protected $_tableName = 'tres';

	public $columns         = array(
		'key_col'           => array('type' => 'int', 'primary_key' => true, 'auto_increment' => true),
		'id'            =>  array('type' => 'int'),
		'field_varchar' =>  array('type' => 'varchar', 'length' => '250', 'default' => 'Hello World'),
		'field_datetime'=>  array('type'  => 'datetime'),
		'field_int'     =>  array('type'  => 'int'),
		'field_int_2'     =>  array('type'  => 'int'),
		'field_dt'    =>  array('type'  => 'date', 'rename' => 'field_date'),
		'field_float_1' => array('type' => 'float', 'default' => '1'),
		'field_varchar_2' => array('type' => 'varchar', 'length' => '10', 'default' => 'Hello'),
		'dosid'     =>  array('type'  => 'int'),
	);

	public $indexes         = array(
		'PRIMARY'   => array('unique' => true, 'columns' => 'key_col'),
		'fk_tres_dos'  => array('columns' => 'field_int_2'),
		'fk_tres_dos_2'  => array('columns' => 'dosid'),
	);
	public $foreignKeys     = array(
		'fk_tres_dos'  =>    array('local_col' => 'field_int_2', 'update' => 'CASCADE', 'delete' => 'CASCADE', 'table' => 'dos', 'col' => 'id'),
		'fk_tres_dos_2'  =>    array('local_col' => 'dosid', 'update' => 'CASCADE', 'delete' => 'CASCADE', 'table' => 'dos', 'col' => 'id'),
	);
}