<?php

namespace PHPCI\Framework\Exception\HttpException;
use PHPCI\Framework\Exception\HttpException;

class ValidationException extends HttpException
{
	protected $errorCode = 400;
	protected $statusMessage = 'Bad Request';
}