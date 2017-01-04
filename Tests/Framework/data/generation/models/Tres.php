<?php

namespace Test\Model\Base;
use PHPCI\Framework\Model;

class Tres extends Model
{
	protected $_tableName = 'tres';

	public $columns         = array(
		'id'            =>  array('type' => 'int'),
		'field_varchar' =>  array('type' => 'varchar', 'length' => '250'),
		'field_date'    =>  array('type'  => 'date'),
		'field_datetime'=>  array('type'  => 'datetime'),
		'field_int'     =>  array('type'  => 'int'),
		'field_int_2'     =>  array('type'  => 'int'),
	);

	public $indexes         = array(
		'fk_tres_uno'  => array('columns' => 'field_int'),
		'fk_tres_dos'  => array('columns' => 'field_int_2'),
	);
	public $foreignKeys     = array(
		'fk_tres_uno'  =>    array('local_col' => 'field_int', 'table' => 'uno', 'col' => 'id'),
		'fk_tres_dos'  =>    array('local_col' => 'field_int_2', 'update' => 'NO ACTION', 'delete' => 'CASCADE', 'table' => 'dos', 'col' => 'id'),
	);
}