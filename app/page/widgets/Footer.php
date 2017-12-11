<?php
namespace module\page\widgets;

class Footer extends \yii\base\Widget
{
    public function run()
    {
        return $this->render('html/footer.phtml');
    }
}