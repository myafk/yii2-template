<?php

return yii\helpers\ArrayHelper::merge(
    [
        'adminEmail' => 'admin@example.com',
        'bsVersion' => '4.x',
        'mdm.admin.configs' => [
            'userTable' => '{{%users}}'
        ]
    ], require(__DIR__ . '/params-local.php')
);
