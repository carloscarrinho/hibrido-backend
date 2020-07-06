<?php

### DATABASE ###
define('CFG_DB_SQLITE', __DIR__ . '/../Database/database.sqlite');
define('CFG_DB_MYSQL', "mysql:host=localhost;dbname=hibridobackend");
define('CFG_DB_USER', 'root');
define('CFG_DB_PASSWORD', '1234');


### LOG ###
define('CFG_LOG_FILE', __DIR__ . '/../Storage/Logs/log.txt');