<?php

namespace PHPCI\Framework\Form\Element;
use PHPCI\Framework\View,
    PHPCI\Framework\Form\Input;

class Checkbox extends Input
{
	protected $_checked;
	protected $_checkedValue;

	public function getCheckedValue()
	{
		return $this->_checkedValue;
	}

	public function setCheckedValue($value)
	{
		$this->_checkedValue = $value;
	}

	public function setValue($value)
	{
		if(is_bool($value) && $value == true)
		{
			$this->_value   = $this->getCheckedValue();
			$this->_checked = true;
			return;
		}

		if($value == $this->getCheckedValue())
		{
			$this->_value   = $this->getCheckedValue();
			$this->_checked = true;
			return;
		}

		$this->_value   = $value;
		$this->_checked = false;
	}

	public function _onPreRender(View &$view)
	{
		parent::_onPreRender($view);
		$view->checkedValue = $this->getCheckedValue();
		$view->checked      = $this->_checked;
	}
}