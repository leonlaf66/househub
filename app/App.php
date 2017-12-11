<?php
class App extends \common\supports\SiteApp
{
    public $autoLanguage = true;
    public $baseUrl = '/';
    public $loginUrl = '';
    public $logoutUrl = '';
    public $memberUrl = '';
    public $pageMetas = [];

    public function bootstrap()
    {
        $this->initSite();
        parent::bootstrap();
    }

    protected function initSite()
    {
        $cookies = \WS::$app->response->cookies;

        $areaId = null;

        if (isset($cookies['area_id'])) {
            $areaId = $cookies->getValue('area_id');
            $this->area->initArea($areaId);
        } else {
            $parts = explode('.', $_SERVER["HTTP_HOST"]);
            $areaId = $parts[0];

            $this->area->initArea($areaId);

            if ($this->area->id) {
                // 记录区域
                \WS::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'area_id',
                    'value' => $areaId,
                    'expire' => 0,
                    'domain' => domain()
                ]));

                \WS::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'house_base_url',
                    'value' => $this->request->getHostInfo().'/',
                    'expire' => 0,
                    'domain' => domain()
                ]));
            }
        }
    }
}
