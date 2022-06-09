<?php

ini_set('mysql.connect_timeout', 3600);

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=qeep',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
