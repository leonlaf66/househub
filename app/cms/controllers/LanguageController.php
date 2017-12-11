<?php
namespace module\cms\controllers;

use WS;
use yii\db\Query;

class LanguageController extends \yii\web\Controller
{
    public function actionSave($category, $source, $translation, $lang='zh-CN')
    {
        if (! WS::$app->translationStatus) return false;

        \common\supports\Language::submit($category, $source, $translation, $lang);

        $cacheKey = [
            'yii\i18n\DbMessageSource',
            $category,
            $lang,
        ];
        $cacheKey = WS::$app->cache->buildKey($cacheKey);
        WS::$app->cache->delete($cacheKey);

        echo 1;
        WS::$app->end();
    }
}