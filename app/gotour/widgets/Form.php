<?php
namespace module\gotour\widgets;

class Form extends \yii\base\Widget
{
    public $rets;

    public function run()
    {  
        return $this->render('form.phtml', ['rets'=>$this->rets]);  
    }
}