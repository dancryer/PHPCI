<?php

namespace PHPCI\Framework;

use PHPCI\Framework\Form\FieldSet;

class Form extends FieldSet
{
	protected $_action = '';
	protected $_method = 'POST';

	public function getAction()
	{
		return $this->_action;
	}

	public function setAction($action)
	{
		$this->_action = $action;
	}

	public function getMethod()
	{
		return $this->_method;
	}

	public function setMethod($method)
	{
		$this->_method = $method;
	}

	protected function _onPreRender(View &$view)
	{
		$view->action   = $this->getAction();
		$view->method   = $this->getMethod();

		parent::_onPreRender($view);
	}

	public function __toString()
	{
		return $this->render();
	}
}