<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

return array(
    /** Loggers attached to every command */
    "_" => function() {
        return array(
            new \Monolog\Handler\StreamHandler(__DIR__ . DIRECTORY_SEPARATOR . 'errors.log', \Monolog\Logger::ERROR),
        );
    },
    /** Loggers for the RunCommand */
    'RunCommand' => function() {
        return array(
            new \Monolog\Handler\RotatingFileHandler(__DIR__ . DIRECTORY_SEPARATOR . 'everything',3, \Monolog\Logger::DEBUG),
        );
    },
);
