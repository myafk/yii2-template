<?php

ini_set('mysql.connect_timeout', 3600);

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=qeep_db;port=3306;dbname=qeep',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
];
