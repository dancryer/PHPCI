<?php

namespace PHPCI\Framework\Form\Element;
use PHPCI\Framework\Form\Element\Text,
    PHPCI\Framework\View;

class TextArea extends Text
{
	protected $_rows = 4;

	public function getRows()
	{
		return $this->_rows;
	}

	public function setRows($rows)
	{
		$this->_rows = $rows;
	}

	protected function _onPreRender(View &$view)
	{
		parent::_onPreRender($view);
		$view->rows = $this->getRows();
	}
}