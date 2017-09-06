<?php

namespace PHPCI\Framework\Exception\HttpException;

use PHPCI\Framework\Exception\HttpException;

class NotFoundException extends HttpException
{
    protected $errorCode = 404;
    protected $statusMessage = 'Not Found';
}