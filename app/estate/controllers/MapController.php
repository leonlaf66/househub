<?php
namespace module\estate\controllers;

use WS;
use module\core\Controller;
use module\estate\helpers;

class MapController extends Controller
{
    public $layout = '@module/page/views/layouts/main2_nofooter.phtml';
    
    public function actionIndex($type = 'purchase')
    {
        return $this->render('index.phtml', [
            'type' => $type,
            'dicts' => [
                'filterRules' => helpers\MapSearch::filterRules()[$type],
                // 'subwaies' => \models\SubwayStation::dictOptions(),
            ],
            'searchAutocompleteItems' => helpers\SearchAutocomplete::map($type !== 'purchase')
        ]);
    }

    public function actionSearch($type = 'purchase')
    {
        $req = \Yii::$app->request;
        $area = WS::$app->area;

        $query = (new \yii\db\Query())
            ->from('listhub_index')
            ->select('id, prop_type, list_price, latitude, longitude')
            ->where(['state' => $area->getStateId()])
            ->andWhere('latitude is not null and longitude is not null')
            ->andWhere(['is_show' => true])
            ->limit(4000);

        if ($type === 'purchase') {
            $query->andWhere(['<>', 'prop_type', 'RN']);
        } else {
            $query->andWhere(['prop_type' => 'RN']);
        }

        $city = helpers\MapSearch::applySearchLocation($query, $req->post('mode_val', null), $req->post('mode', 'areas'));
        helpers\MapSearch::applyFilters($query, $req->post('filters', []), helpers\MapSearch::filterRules()[$type]);

        if (! $city) {
            return $this->ajaxJson([
                'city' => null,
                'cityPolygons' => [],
                'items' => []
            ]);
        }

        $propTypeCodeIds = \common\estate\Rets::propertyTypeOptions();
        $items = array_map(function ($d) use($propTypeCodeIds) {
            return implode('|', [
                $d['id'],
                $d['latitude'],
                $d['longitude'],
                floatval($d['list_price']),
                $propTypeCodeIds[$d['prop_type']] ?? ''
            ]);
        }, $query->all());

        // 获取城市边界
        $cityName = $city->name;
        $cityId = strtoupper(str_replace(' ', '-', $cityName));
        $cityPolygons = WS::getStaticData('polygons/'.$area->getStateId().'/'.$cityId, []);

        return $this->ajaxJson([
            'type' => $type,
            'city' => $cityName,
            'cityPolygons' => $cityPolygons,
            'items' => $items,
        ]);
    }

    public function actionDetail($id)
    {
        $rets = \common\listhub\estate\House::findOne($id);

        return $this->ajaxJson([
            'id'=>$id,
            'image_url' => $rets->getPhoto(0)['url'],
            'name' => $rets->getFieldData('title'),
            'location' => $rets->getFieldData('location'),
            'list_price' => $rets->getFieldData('list_price'),
            'status_name' => $rets->getFieldData('status_name'),
            'no_bedrooms' => $rets->getFieldData('no_bedrooms', ['title'=>tt('Beds', '卧室数')]),
            'no_full_baths' => $rets->getFieldData('no_full_baths', ['title'=>tt('Full baths', '全卫')]),
            'no_half_baths' => $rets->getFieldData('no_half_baths', ['title'=>tt('Half baths', '半卫')]),
            'square_feet' => $rets->getFieldData('square_feet', ['title'=>tt('Living', '面积')]),
            'no_list_days' => $rets->getFieldData('no_list_days')
        ]);
    }
}