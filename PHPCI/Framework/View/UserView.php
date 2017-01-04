<?php

namespace PHPCI\Framework\View;

use PHPCI\Framework\View\Template;

class UserView extends Template
{
    public function __construct($string)
    {
        trigger_error('Use of UserView is now deprecated. Please use Template instead.', E_USER_NOTICE);
        parent::__construct($string);
    }
}