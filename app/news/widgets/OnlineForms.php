<?php
namespace module\news\widgets;

class OnlineForms extends \yii\base\Widget
{
    public function run()
    {
        $items = \WS::getStaticData('online.forms');

        return $this->render('online-forms.phtml', [
            'items'=>$items
        ]);
    }
}