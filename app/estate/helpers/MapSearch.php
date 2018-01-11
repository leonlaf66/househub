<?php
namespace module\estate\helpers;

use WS;
use \yii\helpers\ArrayHelper as AH;

/**
 * 处理地图查询助手(pc网站专用)
 */
class MapSearch
{
    public static function applySearchLocation($query, $modeValue, $modeName)
    {
        $callName = 'apply'.ucfirst($modeName);
        return self::$callName($query, $modeValue);
    }
    public static function applyAreas($query, $q)
    {
        $stateId = WS::$app->area->getStateId();

        $city = null;
        if (! empty($q)) {
            if (is_numeric($q) && strlen($q) === 5) {
                $query->andWhere(['zip_code' => $q]);
                $city = \models\City::findByPostalcode(\WS::$app->area->stateId, $q);
            } elseif (preg_match('/[a-zA-Z]{0,2}[0-9]{5,10}/', $q)) {
                $query->andWhere(['id' => $q]);
                if ($rets = \common\listhub\estate\House::findOne($q)) {
                    $city = $rets->city;
                }
            } else {
                $cityName = ucwords($q);
                $city = \models\City::findByName(\WS::$app->area->stateId, $cityName);
                if ($city) {
                    $query->andWhere('city_id=:city_id or parent_city_id=:city_id', [':city_id' => $city->id]);
                } else {
                    $query->where('1=2');
                }
            }
        }

        return $city;
    }

    public static function applySubwaies($query, $subway)
    {
        $stationIds = $subway['stationIds'];
        $stationIdsExpr = '{'.implode(',', $stationIds).'}';
        $query->andFilterWhere(['&&', 'subway_stations', $stationIdsExpr]);

        return null;
    }

    public static function applyFilters($query, $filters, $filterRules)
    {
        foreach ($filters as $filterId => $val) {
            if (isset($filterRules[$filterId])) {
                $filterRule = $filterRules[$filterId];
                if (isset($filterRule['apply'])) {
                    ($filterRule['apply'])($query, $val, $filterRule);
                }
            }
        }
    }

    public static function filterRules()
    {
        $propertyTypes = \common\estate\Rets::propertyTypes();
        unset($propertyTypes['RN']);

        $rules['purchase'] = [
            'prop_type' => [
                'title' => tt('Type', '类型'),
                'options' => $propertyTypes,
                'apply' => function ($query, $vals) {
                    $query->andFilterWhere(['in', 'prop_type', $vals]);
                }
            ],
            'beds' => [
                'title' => tt('Bedroom', '卧室'),
                'options' => [
                    1 => '1+',
                    2 => '2+',
                    3 => '3+',
                    4 => '4+',
                    5 => '5+'
                ],
                'apply' => function ($query, $val) {
                    $query->andFilterWhere(['>', 'no_bedrooms', $val]);
                }
            ],
            'baths' => [
                'title' => tt('Bathroom', '卫生间'),
                'options' => [
                    1 => '1+',
                    2 => '2+',
                    3 => '3+',
                    4 => '4+',
                    5 => '5+'
                ],
                'apply' => function ($query, $val) {
                    $query->andFilterWhere(['>', 'no_bathrooms', $val]);
                }
            ],
            'parking' => [
                'title' => tt('Parking', '车位'),
                'options' => [
                    '1' => '1+',
                    '2' => '2+',
                    '3' => '3+'
                ],
                'apply' => function ($query, $val) {
                    $query->andFilterWhere(['>', 'parking_spaces', $val]);
                }
            ],
            'market-days' => [
                'title' => tt('Market Days', '上市天数'),
                'options' => [
                    '1' => tt('New listing', '最新'),
                    '2' => tt('This week', '本周'),
                    '3' => tt('This month', '本月')
                ],
                'apply' => function ($query, $val) {
                    $getRangeFns = [
                        '1'=>function(){
                            $now = time();
                            return [$now - 86400 * 2, $now];
                        },
                        '2'=>function(){
                            $now = time();
                            return [$now - 86400 * 7, $now];
                        },
                        '3'=>function(){
                            $now = time();
                            return [$now - 86400 * 30, $now];
                        }
                    ];

                    if(isset($getRangeFns[$val])) {
                        $getRangeFn = $getRangeFns[$val];
                        list($start, $end) = $getRangeFn();
                        $start = date('Y-m-d', $start);
                        $end = date('Y-m-d', $end);
                        $query->andWhere('list_date>=:start and list_date <=:end', [
                            ':start'=>$start,
                            ':end'=>$end
                        ]);
                    }
                }
            ],
            'price' => [
                'title' => tt('Pirce', '售价'),
                'options' => [
                    '0~500000' => tt('0-500,000', '50万以下'),
                    '500000~1000000' => tt('500,000-1,000,000', '50-100万'),
                    '1000000~1500000' => tt('1,000,000-1,500,000', '100-150万'),
                    '1500000~2000000' => tt('1,500,000~2,000,000', '150-200万'),
                    '2000000~999999999999' => tt('2,000,000+', '200万以上')
                ],
                'custom' => [
                    'type' => 'range',
                    'title' => tt('$', '万美元')
                ],
                'apply' => function ($query, $val) {
                    if (substr($val, 0, 1) === '@' && \WS::$app->language === 'zh-CN') { // 需要转换单位(万美元单位)
                        $val = substr($val, 1);
                        list($start, $end) = explode('~', $val);
                        $start *= 10000;
                        $end *= 10000;
                    } else {
                        list($start, $end) = explode('~', $val);
                    }
                    
                    $query->andFilterWhere(['>=', 'list_price', $start]);
                    $query->andFilterWhere(['<=', 'list_price', $end]);
                }
            ],
            'square' => [
                'title' => tt('Living area', '居住面积'),
                'options' => [
                    '0~1000' => tt('0-1000', '0-100'),
                    '1000~2000' => tt('1000-2000', '100-200'),
                    '2000~3000' => tt('2000-3000', '200-300'),
                    '3000~999999999999' => tt('3000+', '300+')
                ],
                'custom' => [
                    'type' => 'range',
                    'title' => tt('Sq.Ft', '平方米')
                ],
                'apply' => function ($query, $val) {
                    if (substr($val, 0, 1) === '@' && \WS::$app->language === 'zh-CN') { // 需要转换单位(平方米单位)
                        $val = substr($val, 1);
                        list($start, $end) = explode('~', $val);
                        $start /= 0.092903;
                        $end /= 0.092903;
                    } else {
                        list($start, $end) = explode('~', $val);
                    }
                    $query->andFilterWhere(['>=', 'square_feet', $start]);
                    $query->andFilterWhere(['<=', 'square_feet', $end]);
                }
            ],
        ];

        $rules['lease'] = [
            'beds' => [
                'title' => tt('Bedroom', '卧室'),
                'options' => [
                    1 => '1+',
                    2 => '2+',
                    3 => '3+',
                    4 => '4+',
                    5 => '5+'
                ],
                'apply' => function ($query, $val) {
                    $query->andFilterWhere(['>', 'no_bedrooms', $val]);
                }
            ],
            'baths' => [
                'title' => tt('Bathroom', '卫生间'),
                'options' => [
                    1 => '1+',
                    2 => '2+',
                    3 => '3+',
                    4 => '4+',
                    5 => '5+'
                ],
                'apply' => function ($query, $val) {
                    $query->andFilterWhere(['>', 'no_bathrooms', $val]);
                }
            ],
            'parking' => [
                'title' => tt('Parking', '车位'),
                'options' => [
                    '1' => '1+',
                    '2' => '2+',
                    '3' => '3+'
                ],
                'apply' => function ($query, $val) {
                    $query->andFilterWhere(['>', 'parking_spaces', $val]);
                }
            ],
            'price' => [
                'title' => tt('Price', '租金'),
                'options' => [
                    '0~1000' => '0-1,000',
                    '1000~1500' => '1,000-1,500',
                    '1500~2000' => '1,500-2,000',
                    '2000~99999999' => '2,000+'
                ],
                'custom' => [
                    'type' => 'range',
                    'title' => tt('$', '美元')
                ],
                'apply' => function ($query, $val) {
                    if (substr($val, 0, 1) === '@') {
                        $val = substr($val, 1);
                    }
                    list($start, $end) = explode('~', $val);
                    $query->andFilterWhere(['>=', 'list_price', $start]);
                    $query->andFilterWhere(['<=', 'list_price', $end]);
                }
            ],
        ];

        return $rules;
    }
}