<?php

namespace app\modules\todo\controllers;

use app\models\TodoList;
use app\models\TodoUser;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;


/**
 * Default controller for the `todo` module
 */
class TaskController extends ActiveController
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
                'Access-Control-Allow-Credentials' => true,
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

    // public function actionOptions()
    // {
    //     // file_put_contents("test.log", 'ok');
    //     return '';
    // }



    public function actionCreateTask()
    {
        $post = Yii::$app->request->post();

        if ($post) {

            $model = new TodoList();
            $model->data = $post;
            $model->user_id = Yii::$app->user->id;
            if ($model->save()) {
                $post["id"] = $model->id;
                $model->data = $post;
                $model->save();
                return $this->asJson([
                    'data' => [
                        $post
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


    public function actionTasksList()
    {
        if ($this->request->isGet) {
            $data = TodoList::find()
                ->select("data")
                ->where(["user_id" => Yii::$app->user->id])
                ->asArray()
                ->indexBy("id")
                ->column();


            return $this->asJson([
                'data' => [
                    ...$data
                ],
            ]);
        }


        Yii::$app->response->statusCode = 400;

        return $this->asJson([
            'data' => [
                'message' => "Bad request"
            ],
            'code' => 400
        ]);
    }

    public function actionUpdateTask($id)
    {
        if ($this->request->isPatch && $id && $this->request->getRawBody()) {
            if ($model = TodoList::findOne($id)) {
                if ($model->user_id === Yii::$app->user->id) {

                    // return $this->asJson([
                    //     $this->request->getRawBody(),
                    //     $id,
                    //     Yii::$app->user->id
                    // ]);
                    $model->data = json_decode($this->request->getRawBody());
                    if ($model->save()) {
                        return $this->asJson([
                            'data' => [
                                $model->data
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
                } else {
                    Yii::$app->response->statusCode = 404;
                    return $this->asJson([
                        'data' => [
                            'message' => "Task not found"
                        ],
                        'code' => 422
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
