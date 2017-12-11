<?php
namespace module\news\widgets;

use module\news\models\News;;

class ListBlock extends \yii\base\Widget
{
    public $heading = '';
    public $type = ''; // infomation / hot / category
    public $theNews = null;

    public function run()
    {
        $news = News::query(\WS::$app->area->id, 0);
        switch ($this->type) {
            case 'infomation':
                $news->andWhere(['=', 'is_infomation', true]);
                $news->orderBy([
                    'id' => SORT_DESC
                ]);
                break;
            case 'hot':
                $news->andWhere(['=', 'is_hot', true]);
                $news->orderBy([
                    'hits' => SORT_DESC,
                    'created_at' => SORT_DESC
                ]);
                break;
            case 'category':
                $news->andWhere(['=', 'type_id', $this->theNews->type_id]);
                $news->andWhere(['<>', 'id', $this->theNews->id]);
                $news->orderBy([
                    'created_at' => SORT_DESC
                ]);
                break;
        }

        $items = $news->limit(5)->all();

        return $this->render('list-block.phtml', [
            'heading' => $this->heading,
            'items'=>$items
        ]);
    }
}