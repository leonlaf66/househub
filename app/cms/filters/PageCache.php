<?php
namespace module\cms\filters;

class PageCache extends \yii\filters\PageCache
{
    public function init()
    {
        $this->variations = array_merge([
            \WS::$app->language,
            \WS::$app->user->isGuest
        ], $this->variations ?? []);

        return parent::init();
    }

    protected function calculateCacheKey()
    {
        $key = [__CLASS__];
        if ($this->varyByRoute) {
            $key[] = \Yii::$app->requestedRoute.implode('/', \WS::$app->request->getQueryParams());
        }
        if (is_array($this->variations)) {
            foreach ($this->variations as $value) {
                $key[] = $value;
            }
        }
        return $key;
    }
}