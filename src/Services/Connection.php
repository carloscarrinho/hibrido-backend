<?php

namespace Source\Services;

use PDO;

class Connection
{
    public static $instance;

    public static function connect(
        string $dsn = CFG_DB_MYSQL,
        string $username = CFG_DB_USER,
        string $password = CFG_DB_PASSWORD
    ): ?PDO {
        if(empty(self::$instance)) {
            self::$instance = new PDO($dsn, $username, $password);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}