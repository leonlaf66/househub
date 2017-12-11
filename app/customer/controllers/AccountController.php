<?php
namespace module\customer\controllers;

use WS;

class AccountController extends \yii\web\Controller
{
    public function actionCheckLogin()
    {
        $status = ! WS::$app->user->isGuest;
        if($status) {
            $userId = WS::$app->user->id;
            $profile = \common\customer\Profile::findOne($userId);
            if(!$profile || !$profile->phone_number) {
                $status = 999;
            }
        }
        echo json_encode($status);
        WS::$app->end();
    }
}