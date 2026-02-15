<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'sdasdfa~!@#sdfasfw893yo2h3we',
            "baseUrl" => "",
            'enableCsrfValidation' => false,
            // 'parsers' => [
            //     'application/json' => 'yii\web\JsonParser',
            // ],
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'multipart/form-data' => 'yii\web\MultipartFormDataParser',
            ]
        ],
        'response' => [
            // ...
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // используем "pretty" в режиме отладки
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                    // ...
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\TodoUser',
            'enableAutoLogin' => true,
            'enableSession' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
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
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [

                "POST api/todo/users" => "todo/user/user-create",
                "OPTIONS api/todo/users" => "todo/user/options",

                "GET api/todo/users/auth" => "todo/user/get-auth",
                "OPTIONS api/todo/users/auth" => "todo/user/options",


                "POST api/todo/users/login" => "todo/user/login",
                "OPTIONS api/todo/users/login" => "todo/user/options",

                "POST api/todo/tasks" => "todo/task/create-task",
                "OPTIONS api/todo/tasks" => "todo/task/options",

                "PATCH api/todo/tasks/<id:\d+>" => "todo/task/update-task",
                "OPTIONS api/todo/tasks/<id:\d+>" => "todo/task/options",

                "GET api/todo/tasks" => "todo/task/tasks-list",
                "OPTIONS api/todo/tasks" => "todo/task/options",


                // "GET api/todo/task-test" => "todo/task/task-test",
                // "OPTIONS api/todo/task-test" => "todo/task/options",

                "GET api" => "site/info",
                "GET /" => "site/info",
                "GET api/todo" => "site/info",
                "GET api/e-comerce" => "site/info",

                // [
                //     'class' => 'yii\rest\UrlRule',
                //     'controller' => 'todo/user',
                //     'prefix' => "api/",
                //     'except' => ['delete',  'update'],
                //     'extraPatterns' => [
                //         'POST logout' => 'logout',
                //     ],
                //     // "pluralize" => false,
                // ],
            ],
        ],

    ],
    'modules' => [
        'todo' => [
            'class' => 'app\modules\todo\Module',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['5.144.96.34'],
    ];
}

return $config;
