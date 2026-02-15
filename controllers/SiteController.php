<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */


    /**
     * {@inheritdoc}
     */
    // public function actions()
    // {
    //     return [
    //         'error' => [
    //             'class' => 'yii\web\ErrorAction',
    //         ],            
    //     ];
    // }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionInfo()
    {
        return $this->asJson([
            "data" => [
                "message" => "api is run"
            ]
        ]);
    }

    public function actionError()
    {
        return $this->asJson([
            "data" => [
                "message" => "error runtime"
            ]
        ]);
    }
}
