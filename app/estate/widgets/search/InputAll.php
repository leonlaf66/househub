<?php
namespace module\estate\widgets\search;

class InputAll extends \yii\base\Widget 
{
    public $types = [
        [
            'id'=>'q-purchase',
            'name'=>'Buy & Sell', 
            'requestUrl'=>'estate/autocomplete/index',
            'resultUrl'=>['estate/house/index', 'type' => 'purchase'],
            'active'=>true
        ],
        [
            'id'=>'q-lease',
            'name'=>'Rental',
            'requestUrl'=>'estate/autocomplete/index',
            'resultUrl'=>['estate/house/index', 'type' => 'lease'],
            'active'=>false
        ]
    ];

    public function run()
    {  
        $types = $this->types;
        /*
        $types[0]['requestUrl'] = create_url($types[0]['requestUrl']);
        $types[0]['resultUrl'] = create_url($types[0]['resultUrl']);

        $types[1]['requestUrl'] = create_url($types[1]['requestUrl']);
        $types[1]['resultUrl'] = create_url($types[1]['resultUrl']);
        */

        return $this->render('input-all.phtml', [
            'types'=>$types
        ]);  
    }
}