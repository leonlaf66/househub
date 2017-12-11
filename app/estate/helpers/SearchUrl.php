<?php
namespace module\estate\helpers;

use WS;
use \yii\helpers\ArrayHelper as AH;
use module\cms\helpers\UrlParamEncoder;

class SearchUrl
{
    public static function to($key, $value)
    {
        list($property, $tab) = self::getBaseInfo();
        $newUrlParamValue = UrlParamEncoder::$inst->setParam($key, $value);
        if ($newUrlParamValue === '') {
            $url = \yii\helpers\Url::to('/house/'.$property.$tab.'/');
        } else {
            $url = '/house/'.$property.$tab.'/'.$newUrlParamValue.'/';
        }
        
        $params = [];

        $q = \yii::$app->request->get('q');
        if ($q) {
            $params['q'] = $q;
        }
        
        if ($key !== 'price' && isset($_GET['cp'])) {
            $params['cp'] = $_GET['cp'];
        }
        if ($key !== 'square' && isset($_GET['cs'])) {
            $params['cs'] = $_GET['cs'];
        }

        return \yii\helpers\Url::to(array_merge([$url], $params));
    }

    public static function replaceTo($key, $value)
    {
        list($property, $tab) = self::getBaseInfo();
        $newUrlParamValue = UrlParamEncoder::$inst->setParam($key, $value, true);
        if ($newUrlParamValue === '') {
            return \yii\helpers\Url::to('/house/'.$property.$tab.'/');
        }

        $url = '/house/'.$property.$tab.'/'.$newUrlParamValue.'/';
        $q = \yii::$app->request->get('q');
        if ($q) {
            $url.= '?q='.urlencode($q);
        }

        return \yii\helpers\Url::to($url);
    }

    public static function getBaseInfo()
    {
        $property = WS::$app->share('rets.property');
        $tab = WS::$app->share('rets.tab');
        if ($tab === 'search') {
            $tab = '';
        } else {
            $tab = '/'.$tab;
        }

        return [$property, $tab];
    }
}