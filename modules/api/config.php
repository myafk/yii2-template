<?php

return [
    'components' => [
        'request' => [
            'class' => '\yii\web\Request',
            'cookieValidationKey' => 'K32113bJxxnF618KiytR9FyZUe7nXiwq',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null) {
                    if ($response->isSuccessful && isset($response->data['status'])) {
                        unset($response->data['status']);
                    }
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                    ];
                    $response->statusCode = 200;
                }
            },
        ],
        'user' => [
            'class' => '\yii\web\User',
            'identityClass' => 'app\modules\api\models\ApiToken',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
    ]
];
