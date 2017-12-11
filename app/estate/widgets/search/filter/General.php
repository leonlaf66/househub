<?php
namespace module\estate\widgets\search\filter;

use WS;
use yii\helpers\Url;
use module\estate\helpers\Rets as RetsHelper;
use module\estate\helpers\SearchUrl;

class General extends \yii\base\Widget 
{  
    public $search = null;

    public function run()
    {  
        $search = $this->search;

        if (isset($_GET['cp'])) {
            $cp = $_GET['custom-price'] = $_GET['cp'];
            list($start, $end) = explode('-', $cp);
            $start = intval($start);
            $end = intval($end);

            if (WS::$app->language === 'zh-CN' && WS::$app->request->get('type') === 'purchase') { // 万美元单位
                $start = $start * 10000;
                $end = $end * 10000;
            }

            $search->query->andWhere(['>', 'list_price', $start]);
            $search->query->andWhere(['<', 'list_price', $end]);
        }

        if (isset($_GET['cs'])) {
            $cs = $_GET['custom-square'] = $_GET['cs'];
            list($start, $end) = explode('-', $cs);
            $start = intval($start);
            $end = intval($end);

            if (WS::$app->language === 'zh-CN') { // 平方米单位
                $start = $start / 0.092903;
                $end = $end / 0.092903;
            }

            $search->query->andWhere(['>', 'square_feet', $start]);
            $search->query->andWhere(['<', 'square_feet', $end]);
        }

        $filters = $this->getRules('generalFilters');
        foreach($filters as $filterId=>$filterOptions) {
            if (isset($_GET[$filterId]) && isset($filterOptions['apply'])) {
                $values = $filterOptions['values'] ?? null;
                ($filterOptions['apply'])($_GET[$filterId], $search, $values);
            }
        }

        return $this->render('general.phtml', [
            'self'=>$this,
            'rules'=>$filters
        ]);
    }

    public function activeClass($ruleId, $value, $className = 'active')
    {
        if ($ruleId === 'price' && isset($_GET['cp'])) {
            return '';
        }
        if ($ruleId === 'square' && isset($_GET['cs'])) {
            return '';
        }

        if ($ruleId === 'property') {
            $values = $this->parseMultipleValues($ruleId);
            if (count($values) === 0 && is_null($value)) {
                return $className;
            }
            return in_array($value, $values) ? $className : '';
        }

        return WS::$app->request->get($ruleId) == $value ? $className : '';
    }

    public function parseRangeValues($field, $s = '-')
    {
        $items = ['', ''];
        if ($customFieldValue = WS::$app->request->get('custom-'.$field)) {
            $items = explode($s, $customFieldValue);
        }
        if (count($items) === 0) $items = ['', '']; 
        if (count($items) === 1) $items[] = '';
        return $items;
    }

    public function getRules($scope)
    {
        $property = WS::$app->share('rets.property');
        $rules = RetsHelper::getSearchRules($property);
        return isset($rules[$scope]) ? $rules[$scope] :[];
    }

    public function createUrl($name, $value)
    {
        if ($name === 'property') { // 多选支持
            return $this->createMultipleUrl($name, $value);
        }

        return SearchUrl::to($name, $value);
    }

    public function createMultipleUrl($name, $value)
    {
        if (! $value) {
            return SearchUrl::to($name, null);
        }

        $values = $this->parseMultipleValues($name);
        if (count($values) === 1 && $values[0] === $value) {
            return SearchUrl::to($name, null);
        }

        if (! in_array($value, $values)) {
            $values[] = $value;
        } else {
            $finedKey = array_search($value, $values);
            if ($finedKey !== false) {
                array_splice($values, $finedKey, 1);
            }
        }

        if (count($values) === 0) {
            return SearchUrl::to($name, null);
        }

        return SearchUrl::to($name, implode('2', $values));
    }

    public function clearMultipleUrl($name, $value)
    {
        $values = $this->parseMultipleValues($name);
        
        $finedKey = array_search($value, $values);
        if (false !== $finedKey) {
            array_splice($values, $finedKey, 1);
        }

        if (count($values) === 0) {
            return SearchUrl::to($name, null);
        }

        return SearchUrl::to($name, implode('2', $values));
    }

    public function parseMultipleValues($name)
    {
        $values = isset($_GET[$name]) ? $_GET[$name] : null;
        if ($values) {
            $values = explode('2', $values);
        } else {
            $values = [];
        }
        return $values;
    }

    public function multipleIn($name, $id)
    {
        $values = $this->parseMultipleValues($name);
        return in_array($id, $values);
    }
}