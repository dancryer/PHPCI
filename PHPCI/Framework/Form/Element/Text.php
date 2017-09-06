<?php

namespace PHPCI\Framework\Form\Element;

use PHPCI\Framework\Form\Input;
use PHPCI\Framework\View;

class Text extends Input
{
    protected function _onPreRender(View &$view)
    {
        parent::_onPreRender($view);
        $view->type = 'text';
    }
}