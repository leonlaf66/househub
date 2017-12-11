<?php
namespace module\home\widgets;

class MarketingGuarantee extends \yii\base\Widget
{
    public function run()
    {  
        return $this->render('marketing-guarantee.phtml', [
            'items' => \WS::getStaticData('home.declarations')
        ]);  
    }
}