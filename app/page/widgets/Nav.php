<?php
namespace module\page\widgets;

class Nav extends \yii\base\Widget
{
    public function run()
    {
        return $this->render('html/nav.phtml');
    }
}