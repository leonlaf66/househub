<?php
namespace module\estate\widgets\view;

use WS;

class Area extends \yii\base\Widget 
{  
    public $rets = null;

    public function run()
    {  
        return $this->render('area.phtml', [
            'rets'=>$this->rets
        ]);
    }
}