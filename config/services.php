<?php

$params = require(__DIR__ . '/params.php');

return [
    'bootstrap' => function () use ($params) {
        return new \app\components\base\Bootstrap();
    },
];
