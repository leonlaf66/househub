<?php
namespace module\yellowpage\controllers;

use WS;
use module\core\models as coreModel;

class SearchController extends \yii\web\Controller
{
	const SORT_VAR = 'sort';
    const CITY_FILTER_VAR = 'city';

    public function actionResult($type=null, $city=null, $sort=null, $dir='')
    {
        $collection = \models\YellowPage::find()
            ->where([
                'area_id' => WS::$app->area->id
            ]);
        
        if($type) {
            $typeId = intval($type);
            $collection->innerJoinWith([
                'type'=>function($query) use($typeId) {
                    $query->where('type_id='.$typeId);
                }
            ]);
        }
        if($city) {
            $cityId = intval($city);
            $collection->innerJoinWith([
                'city'=>function($query) use($cityId) {
                    $query->where('city_id='.$cityId);
                }
            ]);
        }
        $collection->distinct();
        if($sort) {
            $dir = $dir === '1' ? 'ASC' : 'DESC';
            switch ($sort) {
                case 'comments':
                    $collection->orderBy('comments '.$dir);
                    break;
                case 'rating':
                    $collection->orderBy('rating '.$dir);
                    break;
                case 'hits':
                    $collection->orderBy('hits '.$dir);
                    break;
                default:
                    $collection->orderBy('weight '.$dir);
                    break;
            }
        }

        $pages = new \yii\data\Pagination([
            'totalCount' =>$collection->count(),
            'defaultPageSize'=>10,
            'pageSizeLimit'=>false
        ]);

        $collection = $collection->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('result.phtml', [
            'yellowPageCollection'=>$collection,
            'pages'=>$pages,
            'city'=>$city,
            'sort'=>$sort
        ]);
    }

    public function createTypeUrl($typeId) 
    {
        return \WS::$app->urlManager->createUrl(['/yellowpage/search/', 'type'=>$typeId]);
    }

    public function createViewUrl($id) 
    {
        return \WS::$app->urlManager->createUrl(['/yellowpage/default/view', 'id'=>$id]);
    }

    public function createItemUrl($id)
    {
        return '';
    }
}