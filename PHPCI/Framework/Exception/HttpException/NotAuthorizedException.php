<?php

namespace PHPCI\Framework\Exception\HttpException;

use PHPCI\Framework\Exception\HttpException;

class NotAuthorizedException extends HttpException
{
    protected $errorCode = 401;
    protected $statusMessage = 'Not Authorized';
}