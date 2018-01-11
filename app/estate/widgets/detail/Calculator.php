<?php
namespace module\estate\widgets\detail;

use models\SiteSetting;

class Calculator extends \yii\base\Widget 
{  
    public $rets = null;

    public function run()
    {
        return $this->render('calculator.phtml', [
            'dataParams' => [
                'ma' => $this->rets->list_price,
                'dp' => 20,
                'mt' => 30,
                'ir' => SiteSetting::get('purchase.mortgage-calculator.interest-rate.default', \WS::$app->area->id),
                'pt' => floatval($this->rets->getXmlElement()->one('Taxes/Tax/Amount'))
            ]
        ]);  
    }
}