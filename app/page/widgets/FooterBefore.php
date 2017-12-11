<?php
namespace module\page\widgets;

class FooterBefore extends \yii\base\Widget
{
    public function run()
    {
        return $this->render('html/footer-before.phtml');
    }
}