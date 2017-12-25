<?php
namespace module\home\widgets;

use WS;

class TopBanner extends \yii\base\Widget
{  
    public function run()
    {  
        return $this->render('top-banner.phtml');  
    }

    public function getQueryText()
    {
        return \WS::$app->request->get('q', '');
    }

    public function totals()
    {
        if (\WS::$app->area->id === 'ma') {
            return \common\estate\Report::totals();
        } else {
            return \common\listhub\estate\Report::totals(\WS::$app->area->stateId);
        }
    }
}