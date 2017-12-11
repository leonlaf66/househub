<?php
namespace module\news\models;

class News extends \models\News
{
    public function getUrl()
    {
        return \WS::$app->urlManager->createUrl(['news/default/view', 'id'=>$this->id]);
    }
}