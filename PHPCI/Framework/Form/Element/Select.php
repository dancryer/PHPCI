<?php

namespace PHPCI\Framework\Form\Element;
use PHPCI\Framework\View,
    PHPCI\Framework\Form\Input;

class Select extends Input
{
	protected $_options = array();

	public function setOptions(array $options)
	{
		$this->_options = $options;
	}

	protected function _onPreRender(View &$view)
	{
		parent::_onPreRender($view);
		$view->options = $this->_options;
	}
}