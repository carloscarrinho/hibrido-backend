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
    public static function connect(): ?PDO {
        if(empty(self::$instance)) {
            self::$instance = new PDO(CFG_DB_MYSQL, CFG_DB_USER, CFG_DB_PASSWORD);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}