<?php

namespace core;

class Database
{
    private static $_pdo;
    public static function getInstance()
    {
        if (!isset(self::$_pdo)) {
            $driver = getenv('DB_DRIVER');
            $database = getenv('DB_DATABASE');
            $host = getenv('DB_HOST');
            $username = getenv('DB_USERNAME');
            $password = getenv('DB_PASSWORD');

            self::$_pdo = new \PDO($driver . ":dbname=" . $database . ";host=" . $host, $username, $password);
        }

        return self::$_pdo;
    }

    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}
}
