<?php

namespace Generation;
use Generation\Model\Uno;

class ArrayPropertyModel extends Uno
{
	public function __construct($initialData = array())
	{
		$this->_getters['array_property'] = 'getArrayProperty';
		self::$sleepable[] = 'array_property';
	}

	public function getArrayProperty()
	{
		return array('one' => 'two', 'three' => array('four' => 'five'));
	}
}