<?php
namespace module\customer\controllers;

use WS;

class RetsFavoriteController extends \yii\web\Controller
{
    public function actionUpdate()
    {
        $result = -1;

        if (! WS::$app->user->isGuest) {
            $result = \common\customer\RetsFavorite::addOrRemove(WS::$app->request->get('list_no', 0), WS::$app->user->id);    
        }

        echo json_encode($result);
    }

    public function actionAdd()
    {
        $result = -1;

        if (! WS::$app->user->isGuest) {
            $result = \common\customer\RetsFavorite::add(WS::$app->request->get('list_no', 0), WS::$app->user->id);    
        }

        echo json_encode($result);
    }

    public function actionRemove()
    {
        $result = -1;

        if (! WS::$app->user->isGuest) {
            $result = \common\customer\RetsFavorite::remove(WS::$app->request->get('list_no', 0), WS::$app->user->id);    
        }

        echo json_encode($result);
    }
}