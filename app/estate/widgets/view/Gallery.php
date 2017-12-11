<?php
namespace module\estate\widgets\view;

use WS;

class Gallery extends \yii\base\Widget 
{  
    public $rets = null;

    public function run()
    {  
        return $this->render('gallery.phtml', [
            'rets'=>$this->rets
        ]);
    }
}