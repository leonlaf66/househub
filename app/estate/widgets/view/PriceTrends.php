<?php
namespace module\estate\widgets\view;

use WS;

class PriceTrends extends \yii\base\Widget 
{  
    public $rets = null;

    public function run()
    {  
        return $this->render('price-trends.phtml', [
            'rets'=>$this->rets
        ]);
    }
}