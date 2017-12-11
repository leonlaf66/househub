<?php
namespace module\home\controllers;

use WS;

class DefaultController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index.phtml');
    }

    public function actionArea()
    {
        $this->layout = '@module/page/views/layouts/simple.phtml';
        return $this->render('area.phtml');
    }
}