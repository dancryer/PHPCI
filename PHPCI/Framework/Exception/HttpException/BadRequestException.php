<?php

namespace PHPCI\Framework\Exception\HttpException;

use PHPCI\Framework\Exception\HttpException;

class BadRequestException extends HttpException
{
    protected $errorCode = 400;
    protected $statusMessage = 'Bad Request';
}