<?php
namespace module\estate\widgets\view;

use WS;

class Around extends \yii\base\Widget 
{  
    public $rets = null;
    public $polygons = [];

    public function run()
    {  
        return $this->render('around.phtml', [
            'rets'=>$this->rets,
            'polygons' => $this->polygons
        ]);
    }
}