<?php
namespace module\yellowpage\controllers;

use module\core\models as coreModel;

class TypeController extends \yii\web\Controller
{
    const SORT_VAR = 'sort';
    const CITY_FILTER_VAR = 'city';

    public function actionIndex($id, $sort=null, $city=null)
    {
        $yellowPageType = coreModel\TaxonomyTerm::find()->where('id=:id', [':id'=>$id])->one();
        
        $collection = \models\YellowPage::find()
            ->where([
                'area_id' => \WS::$app->area->id
            ]);
        if($id) {
            $collection->innerJoinWith([
                'type'=>function($query) use($id) {
                    $query->where('type_id='.$id);
                }
            ]);
        }
        if($sort) {
            switch ($sort) {
                case 'comments':
                    $collection->orderBy('comments desc');
                    break;
                case 'rating':
                    $collection->orderBy('rating desc');
                    break;
                case 'hits':
                    $collection->orderBy('hits desc');
                    break;
                default:
                    $collection->orderBy('yellow_page.weight desc');
                    break;
            }
        }
        if($city) {
            $collection->where('city=:city', [':city'=>$city]);
        }

        $pages = new \yii\data\Pagination([
            'totalCount' =>$collection->count(),
            'defaultPageSize'=>10,
            'pageSizeLimit'=>false
        ]);
        $collection = $collection->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index.phtml', [
            'yellowPageType'=>$yellowPageType,
            'yellowPageCollection'=>$collection,
            'pages'=>$pages,
            'id'=>$id, 
            'sort'=>$sort, 
            'city'=>$city
        ]);
    }

    public function createTypeUrl($id) 
    {
        return \WS::$app->urlManager->createUrl(['/yellowpage/type/index', 'id'=>$id]);
    }

    public function createViewUrl($id) 
    {
        return \WS::$app->urlManager->createUrl(['/yellowpage/default/view', 'id'=>$id]);
    }
}