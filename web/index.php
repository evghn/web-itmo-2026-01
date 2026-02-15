<?php

// comment out the following two lines when deployed to production
// defined('YII_DEBUG') or define('YII_DEBUG', true);
// defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

switch (true) {
    case str_contains($_SERVER["REQUEST_URI"], "/api"):
        if (str_contains($_SERVER["REQUEST_URI"], "/todo")) {
            $config = require __DIR__ . '/../config/web_todo_api.php';
        } else {
            $config = require __DIR__ . '/../config/web_todo_api.php';
        }
        break;
    default:
        $config = require __DIR__ . '/../config/web.php';
}

(new yii\web\Application($config))->run();
