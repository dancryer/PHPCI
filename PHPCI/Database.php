<?php
namespace PHPCI;

use b8\Database as B8Database;

class Database extends B8Database
{
    public static function getConnection($type = 'read')
    {
        if (!self::$initialised) {
            self::init();
        }

        // If the connection hasn't been used for 5 minutes, force a reconnection:
        if (!is_null(self::$lastUsed[$type]) && (time() - self::$lastUsed[$type]) > 300) {
            self::$connections[$type] = null;
        }

        if(is_null(self::$connections[$type]))
        {
            if (is_array(self::$servers[$type])) {
                // Shuffle, so we pick a random server
                $servers = self::$servers[$type];
                shuffle($servers);
            } else {
                // Only one server was specified
                $servers = array(self::$servers[$type]);
            }

            $connection = null;

            // Loop until we get a working connection:
            while(count($servers))
            {
                // Pull the next server:
                $server = array_shift($servers);

                // Try to connect:
                try
                {
                    // Check if host contains a port
                    if (strpos($server, ':') !== false) {
                        list($host, $port) = explode(':', $server);
                        $dsn = 'mysql:host=' . $host . ';port=' . $port;
                    } else {
                        $dsn = 'mysql:host=' . $server;
                    }

                    $connection = new self($dsn . ';dbname=' . self::$details['db'],
                        self::$details['user'],
                        self::$details['pass'],
                        array(
                            \PDO::ATTR_PERSISTENT         => false,
                            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                            \PDO::ATTR_TIMEOUT            => 2,
                            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
                        ));
                }
                catch(\PDOException $ex)
                {
                    $connection = false;
                }

                // Opened a connection? Break the loop:
                if($connection)
                {
                    break;
                }
            }

            // No connection? Oh dear.
            if(!$connection && $type == 'read')
            {
                throw new \Exception('Could not connect to any ' . $type . ' servers.');
            }

            self::$connections[$type] = $connection;
        }

        self::$lastUsed[$type] = time();
        return self::$connections[$type];
    }
} 