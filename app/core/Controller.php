<?php
namespace module\core;
use cyneek\yii2\blade\BladeBehavior;

class Controller extends \yii\web\Controller
{   
    public function behaviors()
    {
        return [
            'blade' => [
                'class' => BladeBehavior::className()
            ]
        ];
    }

    public function ajaxJson($data)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }
}
