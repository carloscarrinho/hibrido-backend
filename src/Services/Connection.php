<?php

namespace Source\Services;

use PDO;

/**
 * Connection Class | Responsável pela conexão com o banco de dados
 * Static Creation Method Pattern
 */
class Connection
{
    public static $instance;
    
    /**
     * Método de conexão com o banco de dados
     *
     * @return void
     */
    public static function connect(
        string $dsn = "mysql:host=localhost;dbname=hibridobackend",
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