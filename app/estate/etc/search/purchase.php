<?php
$propertyNames = \common\listhub\estate\Setting::get('references', 'propTypes', []);
unset($propertyNames['RN']);
$propTypes = [];
foreach($propertyNames as $name=>$lables) {
    if ($name !== 'RN') {
        $propTypes[strtolower($name)] = tt($lables);
    }
}

$t = lang('rets-filters', true);
return [
    'generalFilters'=>[
        'property'=>[
            'heading'=>$t('Type'),
            'options'=>$propTypes,
            'apply'=>function ($val, $search) {
                $propTypes = explode('2', strtoupper($val));
                $search->query->andWhere(['in', 'prop_type', $propTypes]);
            }
        ],
        'price'=>[
            'heading'=>$t('Price'),
            'options'=>[
                '1' => tt('0-500,000', '0-50万'),
                '2' => tt('500,000-1,000,000', '50-100万'),
                '3' => tt('1,000,000-1,500,000', '100-150万'),
                '4' => tt('1,500,000-2,000,000', '150-200万'),
                '5' => tt('2,000,000', '200万+')
            ],
            'values' => [
                '1' => [0, 500000],
                '2' => [500000, 1000000],
                '3' => [1000000, 1500000],
                '4' => [1500000, 2000000],
                '5' => [2000000, 99999999999999]
            ],
            'apply'=>function ($val, $search, $values) {
                list($begin, $end) = $values[$val];

                $search->query->andWhere(['>', 'list_price', $begin]);
                $search->query->andWhere(['<', 'list_price', $end]);
            }
        ],
        'beds'=>[]
    ],
];