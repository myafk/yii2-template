<?php

ini_set('mysql.connect_timeout', 3600);

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=qeep',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
