<?php

$transalationDb = [
    'class' => 'yii\i18n\DbMessageSource',
    'sourceLanguage' => 'ru-RU', // Developer language
    'sourceMessageTable' => '{{%language_source}}',
    'messageTable' => '{{%language_translate}}',
];
$params = require(__DIR__ . '/params.php');
$services = require(__DIR__ . '/services.php');

$config = [
    'id' => 'otech',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'bootstrap'],
    'language' => 'ru',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@uploads' => '@app/web/uploads',
        '@main' => '@app/modules/main'
    ],
    'defaultRoute' => '/user/auth/login',
    'components' => array_merge([
        'request' => [
            'cookieValidationKey' => 'K2OK13bJxxnF618KiytR9FyZUe7nXiwq',
        ],
        'i18n' => [
            'translations' => [
                '*' => $transalationDb,
            ],
        ],
        'formatter' => [
            'class' => 'app\components\i18n\Formatter'
        ],
        'db' => require(__DIR__ . '/db-local.php'),
        'cache' => require(__DIR__ . '/cache-local.php'),
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/logout' => 'user/auth/logout',
                '/flush-cache' => 'settings/system/flush-cache',
                '<module>/<controller>/<action>/<id:\d+>' => '<module>/<controller>/<action>',
            ],
        ],
        'view' => [
            'class' => 'app\components\web\View',
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'user/auth/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ], $services),
    'modules' => [
        'main' => [
            'class' => 'app\modules\main\MainModule',
        ],
        'user' => [
            'class' => 'app\modules\user\UserModule',
        ],
        'profile' => [
            'class' => 'app\modules\profile\ProfileModule',
        ],
        'dashboard' => [
            'class' => 'app\modules\dashboard\DashboardModule',
        ],
        'settings' => [
            'class' => 'app\modules\settings\SettingsModule',
        ],
        'upload' => [
            'class' => 'app\modules\upload\UploadModule',
        ],
        'log' => [
            'class' => 'app\modules\log\LogModule',
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            'as access' => [
                'class' => 'app\components\helpers\MdmAccessControl',
            ],
            'mainLayout' => '@app/views/layouts/main.php',
            'layout' => '@app/views/layouts/adminlte/modules/mdm-admin',
            'menus' => [
                'user' => [],
                'assignment' => 'Назначения',
                'role' => 'Роли',
                'permission' => 'Разрешения',
                'route' => [],
                'rule' => 'Правила',
            ],
        ],
        'translatemanager' => [
            'class' => 'lajax\translatemanager\Module',
            'layout' => '@app/views/layouts/main',
            //'layout' => '@app/views/layouts/adminlte/modules/language', // TODO
            'allowedIPs' => ['*'],
            'as access' => [
                'class' => 'app\components\helpers\TranslateAccessControl',
            ],
            'root' => [
                '@app/components',
                '@app/modules',
                '@app/views',
            ],
            'scanTimeLimit' => 30,
        ],
    ],
    'params' => $params,
];

if (isset($params['cookieDebugParams'], $_COOKIE[$params['cookieDebugParams']])) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];
} elseif (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
}

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
