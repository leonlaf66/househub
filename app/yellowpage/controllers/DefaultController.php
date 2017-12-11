<?php
namespace module\yellowpage\controllers;

use models\YellowPage;

class DefaultController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index.phtml');
    }

    public function actionView($id)
    {
        \WS::$app->assetsAutoCompress->enabled = false;

    	$yellowpage = YellowPage::findOne($id);

        \WS::$app->page->bindParams(['name' => $yellowpage->name]);

        YellowPage::hit($id);
    	return $this->render('view.phtml', ['model'=>$yellowpage]);
    }

    public function actionOncomment()
    {
        $req = \WS::$app->request;
        if($req->isPost) {
            $id = intval($req->get('id', 0));

            $yellowPage = YellowPage::findOne($id);
            $yellowPage->rating = intval($req->get('rating', 0));
            $yellowPage->comments = intval($req->get('total', 0));
            $yellowPage->save();
        }
    }
}