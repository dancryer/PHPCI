<?php

namespace Test\Model\Base;
use PHPCI\Framework\Model;

class BadModel extends Model
{
	protected $_tableName = 'bad_table';

	public $columns         = array(
		'id'            =>  array('type' => 'catfish'),
	);

	public $indexes         = array(
	);
	public $foreignKeys     = array(
	);
}