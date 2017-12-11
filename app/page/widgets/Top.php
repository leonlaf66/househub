<?php
namespace module\page\widgets;

class Top extends \yii\base\Widget
{
    public function run()
    {
        return $this->render('html/top.phtml');
    }
}