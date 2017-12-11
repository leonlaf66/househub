<?php
namespace module\estate\widgets\search;

use WS;
use module\estate\helpers;

class Input extends \yii\base\Widget 
{
    public $id = 'q';
    public $requestUrl = 'estate/autocomplete/index';
    public $resultUrl = ['estate/house/index', 'type' => 'lease']; // /purchase/

    public function init()
    {
        $this->requestUrl = create_url($this->requestUrl);
        $this->resultUrl = create_url($this->resultUrl);

        return parent::init();
    }

    public function run()
    {
        $isLease = WS::$app->share('rets.property') === 'lease';

        return $this->render('input.phtml', [
            'id'=>$this->id,
            'q'=>$this->getQueryText(),
            'requestUrl'=>$this->requestUrl,
            'resultUrl'=>$this->resultUrl,
            'searchAutocompleteItems' => helpers\SearchAutocomplete::map($isLease)
        ]);  
    }

    public function getQueryText()
    {
        return \WS::$app->request->get('q', '');
    }
}