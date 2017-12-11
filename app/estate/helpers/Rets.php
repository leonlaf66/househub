<?php
namespace module\estate\helpers;

use WS;
use \yii\helpers\ArrayHelper as AH;

class Rets extends \common\estate\helpers\Rets
{
    public static function getSearchRules($type)
    {
        $rules = self::getModule()->getConfigs('search/'.$type);
        $rules = AH::merge($rules, 
                self::getModule()->getConfigs('search/base')
            );

        return $rules;
    }

    public static function getRangeType()
    {
        $range = \WS::$app->controller->id; 
        return in_array($range, ['purchase', 'lease']) ? $range : 'purchase';
    }

    public static function getUrl($rets)
    {
        $typeName = $rets->prop_type == 'RN' ? 'lease' : 'purchase';
        return "/{$typeName}/{$rets->list_no}/";
    }

    public static function getModule()
    {
        return WS::$app->getModule('estate');
    }
}