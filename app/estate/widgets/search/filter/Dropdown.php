<?php
namespace module\estate\widgets\search\filter;

use WS;
use yii\helpers\Url;
use module\estate\helpers\Rets as RetsHelper;
use module\cms\helpers\UrlParamEncoder;

class Dropdown extends \yii\base\Widget 
{  
    public $search = null;

    public function run()
    {  
        $search = $this->search;

        $filters = $this->getRules('dropdownFilters');
        foreach($filters as $filterId=>$filterOptions) {
            if (isset($_GET[$filterId]) && isset($filterOptions['apply'])) {
                ($filterOptions['apply'])($_GET[$filterId], $search);
            }
        }

        return $this->render('dropdown.phtml', [
            'self'=>$this,
            'rules'=>$filters
        ]);
    }

    public function selectAttr($ruleId, $value)
    {
        return WS::$app->request->get($ruleId) === (string)$value ? 'selected="selected"' : '';
    }

    public function getSelectName($ruleId)
    {
        $paramValue = \WS::$app->request->get($ruleId);
        if (is_null($paramValue)) return tt('All', '不限');
        $rules = $this->getRules('dropdownFilters');
        return $rules[$ruleId]['options'][$paramValue];
    }

    public function getRules($scope)
    {
        $property = WS::$app->share('rets.property');
        $rules = RetsHelper::getSearchRules($property);
        return isset($rules[$scope]) ? $rules[$scope] :[];
    }

    public function createUrl($name, $value)
    {
        $property = WS::$app->share('rets.property');
        $tab = WS::$app->share('rets.tab');
        if ($tab === 'search') {
            $tab = '';
        } else {
            $tab = '/'.$tab;
        }

        $query = [];
        if (isset($_GET['cp'])) {
            $query[] = "cp={$_GET['cp']}";
        }
        if (isset($_GET['cs'])) {
            $query[] = "cs={$_GET['cs']}";
        }
        if (count($query) > 0) {
            $query = '?'.implode('&', $query);
        } else {
            $query = '';
        }

        $newUrlParamValue = UrlParamEncoder::$inst->setParam($name, $value);
        if ($newUrlParamValue === '') {
            return \yii\helpers\Url::to('/house/'.$property.$tab.'/'.$query);
        }
        return \yii\helpers\Url::to('/house/'.$property.$tab.'/'.$newUrlParamValue.'/'.$query);
    }
}