<?php

namespace app\modules\todo\controllers;

use app\models\TodoUser;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;


/**
 * Default controller for the `todo` module
 */
class UserController extends ActiveController
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
                'Origin' => [isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'http://' . $_SERVER['REMOTE_ADDR']],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                // 'Access-Control-Allow-Credentials' => false,
            ],
        ];

        $auth = [
            'class' => HttpBearerAuth::class,
            'except' => ['options', 'user-create', 'login'],
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



    public function actionUserCreate()
    {
        $model = new TodoUser();
        if ($model->load(Yii::$app->request->post(), "")) {
            $model->password = Yii::$app->security->generatePasswordHash($model->password);
            $model->auth_key = Yii::$app->security->generateRandomString();
            if ($model->save()) {
                return $this->asJson([
                    'data' => [
                        'token' => $model->auth_key
                    ],
                ]);
            } else {
                Yii::$app->response->statusCode = 422;

                return $this->asJson([
                    'data' => [
                        'errors' => $model->errors
                    ],
                    'code' => 422
                ]);
            }
        }

        Yii::$app->response->statusCode = 400;

        return $this->asJson([
            'data' => [
                'message' => "Bad request"
            ],
            'code' => 400
        ]);
    }

    public function actionGetAuth()
    {
        if (Yii::$app->user->id) {

            Yii::$app->response->statusCode = 204;
            return $this->asJson(null);
        }

        Yii::$app->response->statusCode = 400;

        return $this->asJson([
            'data' => [
                'message' => "Bad request"
            ],
            'code' => 400
        ]);
    }

    public function actionLogin()
    {
        $model = new TodoUser();
        if ($model->load(Yii::$app->request->post(), "")) {
            if ($model->password && $model->login) {
                $user = TodoUser::findOne(['login' => $model->login]);
                if ($user && Yii::$app->security->validatePassword($model->password, $user->password)) {
                    $user->auth_key = Yii::$app->security->generateRandomString();
                    if ($user->save()) {
                        return $this->asJson([
                            'data' => [
                                'token' => $user->auth_key
                            ],
                        ]);
                    }
                } else {
                    Yii::$app->response->statusCode = 404;

                    return $this->asJson([
                        'data' => [
                            'message' => "User not found",
                        ],
                        'code' => 404
                    ]);
                }
            }
        }

        Yii::$app->response->statusCode = 400;

        return $this->asJson([
            'data' => [
                'message' => "Bad request"
            ],
            'code' => 400
        ]);
    }
}
