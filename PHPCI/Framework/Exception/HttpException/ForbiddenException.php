<?php

namespace PHPCI\Framework\Exception\HttpException;

use PHPCI\Framework\Exception\HttpException;

class ForbiddenException extends HttpException
{
    protected $errorCode = 403;
    protected $statusMessage = 'Forbidden';
}