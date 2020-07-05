<?php

### URLs ###
define('CFG_URL_BASE', 'http://localhost:8001');

### DATABASE ###
define('CFG_DB_MYSQL', "mysql:host=" . CFG_DB_HOST . ";dbname=" . CFG_DB_NAME);
define('CFG_DB_DRIVER', 'mysql');
define('CFG_DB_HOST', 'localhost');
define('CFG_DB_NAME', 'hibridobackend');
define('CFG_DB_USER', 'root');
define('CFG_DB_PASSWORD', '1234');
define('CFG_DB_OPTIONS', [
    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
    \PDO::ATTR_CASE => \PDO::CASE_NATURAL
]);