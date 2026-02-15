<?php

namespace app\controllers;

use app\models\FileUploadForm;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\VarDumper;
use yii\rest\ActiveController;
use yii\web\UploadedFile;

/**
 * Default controller for the `todo` module
 */
class FileController extends ActiveController
{
    public $modelClass = "";
    // public $modelClass = '';
    public $enableCsrfValidation = false;
    /**
     * Renders the index view for the module
     * @return string
     */

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter

        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                // 'Origin' => ["*"],
                'Origin' => [isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'http ://' . $_SERVER['REMOTE_ADDR']],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false,
            ],
        ];

        $auth = [
            'class' => HttpBearerAuth::class,
            'except' => ['options'],
            // 'only' => ['*'],
        ];
        $behaviors['authenticator'] = $auth;
        return $behaviors;
    }


    public function actions()
    {
        $actions = parent::actions();

        // Отключаем стандартный action index, если хотим полностью заменить его
        unset($actions['index'], $actions["create"]);

        return $actions;
    }

    public function actionResponseFile()
    {
        $model = new FileUploadForm();
        $model->files = UploadedFile::getInstancesByName('files');
        // VarDumper::dump($model->files, 10, true);
        if ($files = $model->upload()) {
            return ['status' => 'success', 'files' => $files];
        } else {
            return $files; // файлов нет ну и не надо  :)
        }
    }
}
