<?php
namespace module\estate\widgets\view;

use WS;

class Recommends extends \yii\base\Widget 
{  
    public $rets = null;

    public function run()
    {  
        return $this->render('recommends.phtml', [
            'rets'=>$this->rets,
            'items'=>\module\estate\models\House::findOne($this->rets->id)->recommends(WS::$app->area->stateId)
        ]);
    }
}