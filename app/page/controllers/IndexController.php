<?php
namespace module\page\controllers;

use WS;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class IndexController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
}
