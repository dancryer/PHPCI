<?php

namespace PHPCI\Framework\Form\Element;
use PHPCI\Framework\Form\Element\Button,
    PHPCI\Framework\View;

class Submit extends Button
{
	protected $_value = 'Submit';

	public function render($viewFile = null)
	{
		return parent::render(($viewFile ? $viewFile : 'Button'));
	}

	protected function _onPreRender(View &$view)
	{
		parent::_onPreRender($view);
		$view->type = 'submit';
	}
}