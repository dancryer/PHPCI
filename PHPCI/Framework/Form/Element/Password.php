<?php

namespace PHPCI\Framework\Form\Element;
use PHPCI\Framework\Form\Element\Text,
    PHPCI\Framework\View;

class Password extends Text
{
	public function render($viewFile = null)
	{
		return parent::render(($viewFile ? $viewFile : 'Text'));
	}

	protected function _onPreRender(View &$view)
	{
		parent::_onPreRender($view);
		$view->type = 'password';
	}
}