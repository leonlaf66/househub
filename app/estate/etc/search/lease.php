<?php
$t = lang('rets-filters', true);
return [
    'generalFilters'=>[
        'price'=>[
            'heading'=>$t('Price'),
            'options'=>[
                '1' => '0-1000',
                '2' => '1000-1500',
                '3' => '1500-2000',
                '4' => '2000+'
            ],
            'values' => [
                '1' => [0, 1000],
                '2' => [1000, 1500],
                '3' => [1500, 2000],
                '4' => [2000, 99999999999999],
            ],
            'apply'=>function ($val, $search, $values) {
                list($begin, $end) = $values[$val];

                $search->query->andWhere(['>', 'list_price', $begin]);
                $search->query->andWhere(['<', 'list_price', $end]);
            }
        ]
    ],
];