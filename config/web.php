<?php

$params      = require __DIR__ . '/params.php';
$paramsLocal = require __DIR__ . '/params-local.php';
$db          = require __DIR__ . '/db.php';

$config = [
    'id'         => 'basic',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => ['log'],
    'aliases'    => [
        '@bower'   => '@vendor/bower-asset',
        '@npm'     => '@vendor/npm-asset',
        '@uploads' => dirname(__DIR__) . '/web/uploads',
        '@images'  => dirname(__DIR__) . '/web/images',
    ],
    'components' => [
        'request'      => [
            'cookieValidationKey' => 'N2daKwGHFEt-a9ITrUvy_XhdFrQXMHWC',
        ],
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
        'user'         => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
            'idParam'         => '__wxmini',
            'identityCookie'  => [
                'name'     => '_identity-wxmini',
                'httpOnly' => true,
            ],
        ],
        'session'      => [
            'name'     => 'app',
            'savePath' => dirname(__DIR__) . '/runtime/session',
        ],
        'adm'          => [
            'class'           => 'yii\web\User',
            'identityClass'   => 'app\models\Admin',
            'enableAutoLogin' => true,
            'loginUrl'        => null,
            'idParam'         => '__admin',
            'identityCookie'  => [
                'name'     => '_identity-adm',
                'httpOnly' => true,
            ],
        ],
        'errorHandler' => [
            'class' => 'app\components\CommonErrorHandler',
        ],
        'mailer'       => [
            'class'            => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => [
                        'error',
                        'warning',
                    ],
                ],
            ],
        ],
        'db'           => $db,
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [],
        ],
    ],
    'params'     => array_merge($params, $paramsLocal),
];

if (YII_ENV_DEV) {
    $config['bootstrap'][]      = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][]    = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
