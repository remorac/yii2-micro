<?php
return [
    'id' => 'micro-app',
    // the basePath of the application will be the `micro-app` directory
    'basePath' => __DIR__,
    // this is where the application will find all controllers
    'controllerNamespace' => 'micro\controllers',
    // set an alias to enable autoloading of classes from the 'micro' namespace
    'aliases' => [
        '@micro' => __DIR__,
    ],
    'components' => [
        'user' => [
            'identityClass' => 'micro\models\User',
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'enableCsrfCookie' => false,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['auth'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST signup' => 'signup',
                        'POST login' => 'login',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['user'],
                    // 'pluralize' => false,
                    // 'extraPatterns' => [
                    //     'POST request-password-reset' => 'request-password-reset',
                    //     'POST password-reset' => 'password-reset',
                    // ],
                ],
            ],
        ],
    ],
];