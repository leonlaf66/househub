<?php
namespace module\yellowpage\widgets;

class Hot extends \yii\base\Widget 
{  
    public function run()
    {  
        return $this->render('home-hot.phtml');  
    }

    public function getItems()
    {
        return \models\YellowPage::find()
            ->where(['area_id' => \WS::$app->area->id])
            ->andWhere('is_top=:istop', [':istop'=>1])
            ->limit(5)
            ->all();
    }

    public function createItemUrl($id) 
    {
        return \WS::$app->urlManager->createUrl(['/yellowpage/default/view', 'id'=>$id]);
    }

    public function createMoreUrl($id) 
    {
        return \WS::$app->urlManager->createUrl(['/yellowpage/default/index']);
    }
}