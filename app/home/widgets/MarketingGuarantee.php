<?php
namespace module\home\widgets;

class MarketingGuarantee extends \yii\base\Widget
{
    public function run()
    {
        $areaId = \WS::$app->area->id;
        return $this->render('marketing-guarantee.phtml', [
            'items' => \WS::getStaticData('home.declarations.'.$areaId)
        ]);  
    }
}