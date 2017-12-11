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
        return \common\estate\Report::totals();
    }
}