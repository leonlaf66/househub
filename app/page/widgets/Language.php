<?php
namespace module\page\widgets;

class Language extends \yii\base\Widget
{
    public function run()
    {
        return $this->render('html/language.phtml');
    }

    public function getNameTitle()
    {
        return \WS::$app->language === 'en-US' ? '<span class="iconfont icon-chinese"></span>中文'
            : '<span class="iconfont icon-english"></span>English';
    }

    public function getUrl()
    {
        $url = \yii\helpers\Url::to();

        if (\WS::$app->language === 'en-US') {    
            $url = '/zh'.$url;
        } else {
            $url = str_replace('/zh/', '/en/', $url);
        }
        return $url;
    }
}