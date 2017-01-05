<?php

namespace PHPCI\Framework\Form\Element;

use PHPCI\Framework\Form\Input;
use PHPCI\Framework\View;

class Select extends Input
{
    protected $_options = [];

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