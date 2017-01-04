<?php

namespace PHPCI\Framework\Form\Element;
use PHPCI\Framework\Form\Input,
    PHPCI\Framework\View;

class Button extends Input
{
	public function validate()
	{
		return true;
	}

	protected function _onPreRender(View &$view)
	{
		parent::_onPreRender($view);
		$view->type = 'button';
	}
}