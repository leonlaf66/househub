<?php
namespace module\news\widgets;

class TopBanner extends \yii\base\Widget
{
    public function run()
    {
        $result = \models\SiteSetting::get('news.banner.top', \WS::$app->area->id);

        return $this->render('top-banners.phtml', [
            'result'=>$result
        ]);
    }

    public function createNewUrl($id)
    {
        $id = trim($id, '#');
        return \WS::$app->urlManager->createUrl(['news/default/view', 'id'=>$id]);
    }
}