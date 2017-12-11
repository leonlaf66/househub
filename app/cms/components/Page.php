<?php
namespace module\cms\components;

use WS;

class Page extends \yii\base\Component
{
    public $id = '';
    public $name = ['', ''];
    public $metas = null;
    public $params = [];

    public function init()
    {
        $this->name = tt($this->name);

        $this->setId(WS::$app->controller->module->id.'/'.WS::$app->controller->id.'/'.WS::$app->controller->action->id);

        return parent::init();
    }

    // 由app bootstrap中调用
    public function end()
    {
        if (is_mobile_request()) {
            $this->rewriteToMobilePage();
        }
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    // 支持webapp跳转
    protected function rewriteToMobilePage()
    {
        $rewrites = include(APP_ROOT.'/config/mobile.rewrites.php');

        $targetUrl = '';
        if (isset($rewrites[$this->id])) {
            $webAppPath = ($rewrites[$this->id])(WS::$app->request);
            if (is_string($webAppPath)) {
                $webAppPath = [$webAppPath];
            }
            $webAppPath['language'] = WS::$app->language;

            $targetUrl = $webAppPath[0];
            unset($webAppPath[0]);
            if (count($webAppPath) > 0) {
                $targetUrl .= '?'.http_build_query($webAppPath);
            }
        } else {
            $targetUrl = '/';
        }

        WS::$app->controller->redirect(WS::$app->params['webApp']['baseUrl'].$targetUrl);
        WS::$app->end();
    }

    public function bindParams($params)
    {
        $this->params = $params;
        return $this;
    }

    public function title()
    {
        return $this->getMeta('title');
    }

    public function metaKeywords()
    {
        return $this->getMeta('keywords');
    }

    public function metaDescription($desc)
    {
        return $this->getMeta('description');
    }

    public function getMeta($field)
    {
        if(! $this->id || $this->id === '') return '';

        static $metasCache = [];
        if (! isset($metasCache[$this->id])) {
            $metasCache[$this->id] = \models\SiteSeoMeta::findOne($this->id);
        }

        $metas = $metasCache[$this->id];
        if (! $metas) return '';

        if (isset($metas[$field])) {
            $text = tt($metas[$field]);
            if (preg_match_all('/%([a-z_\-0-9]+)%/', $text, $matches)) {
                foreach ($matches[1] as $paramId) {
                    if (isset($this->params[$paramId])) {
                        $text = str_replace('%'.$paramId.'%', $this->params[$paramId], $text);
                    }
                }
            }
            return $text;
        }
        return '';
    }
}