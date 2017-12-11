<?php
namespace module\estate\widgets;

class Share extends \yii\base\Widget
{
	public $rets = null;
	
    public function run()
    {
        return $this->render('share.phtml');
    }
}