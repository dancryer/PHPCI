<?php

namespace PHPCI\Framework\Form\Element;

use PHPCI\Framework\View;

class Csrf extends Hidden
{
    protected $_rows = 4;

    public function validate()
    {
        if ($this->_value != $_COOKIE[$this->getName()]) {
            return false;
        }

        return true;
    }

    protected function _onPreRender(View &$view)
    {
        parent::_onPreRender($view);
        $csrf = md5(microtime(true));
        $view->csrf = $csrf;
        setcookie($this->getName(), $csrf);
    }
}