<?php
namespace module\estate\widgets\search;

use module\estate\helpers\SearchUrl;

class Result extends \yii\base\Widget
{
    public $search = null;

    public function run()
    {  
        $search = $this->search;

        return $this->render('result.phtml', [
            'search'=>$search
        ]);  
    }

    public function createPageUrl($page)
    {
        return SearchUrl::to('page', $page);
    }
}