<?php
namespace module\page\widgets;

class Slider extends \yii\base\Widget
{
    public $contentPositon = 'middle';
    
    public function init()
    {
        ob_start();
        return parent::init();
    }
    public function run()
    {
        $content = ob_get_clean();
        return $this->render('html/slider.phtml', ['content'=>$content]);
    }
}