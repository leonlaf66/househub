<?php
namespace module\yellowpage\widgets;

class Gallery extends \yii\base\Widget 
{  
    public $yellowpage = null;

	public function init()
    {
        
    }

    public function run()
    {  
        return $this->render('media-gallery.phtml');  
    }

    public function getImageHelper()
    {
        return \common\helper\Media::init('yellowpage');
    }
}