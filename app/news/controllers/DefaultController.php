<?php
namespace module\news\controllers;

use WS;
use yii\web\Controller;
use models\TaxonomyTerm;
use module\news\models\News;

class DefaultController extends Controller
{
    public function actionIndex()
    {   
        $typeId = intval(\WS::$app->request->get('type', 0));
        $newsProvider = News::search(\WS::$app->area->id, $typeId);

        //$types = News::types();
        $types = TaxonomyTerm::find()
            ->where([
                'taxonomy_id' => 3,
                'parent_id' => 0
            ])
            ->asArray()
            ->all();

        $types = \common\helper\ArrayHelper::index($types, 'id', 'name');

        $pages = new \yii\data\Pagination([
            'totalCount' =>$newsProvider->query->count(),
            'defaultPageSize'=>10,
            'pageSizeLimit'=>false
        ]);

    	return $this->render('index.phtml', [
            'typeId'=>$typeId,
            'types'=>$types,
            'pages'=>$pages,
            'newsProvider'=>$newsProvider
        ]);
    }

    public function actionView($id)
    {
        $news = News::findOne($id);

        WS::$app->page->bindParams(['name' => $news->title]);

        return $this->render('view.phtml', [
            'news'=>$news
        ]);
    }

    public function actionPdf($file)
    {
        return $this->render('pdf-view.phtml', [
            'file'=>$file
        ]);
    }
}