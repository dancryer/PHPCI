<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */
require_once dirname(__FILE__) . '/bootstrap.php';

$writeServers = $config->get('b8.database.servers.write');

if (! is_array($writeServers)) {
    $writeServers = [$writeServers];
}

$conf = [
    'paths' => [
        'migrations' => 'PHPCI/Migrations',
    ],

    'environments' => [
        'default_migration_table' => 'migration',
        'default_database'        => 'phpci',
        'phpci'                   => [
            'adapter' => 'mysql',
            'host'    => end($writeServers),
            'name'    => $config->get('b8.database.name'),
            'user'    => $config->get('b8.database.username'),
            'pass'    => $config->get('b8.database.password'),
        ],
    ],
];

return $conf;
