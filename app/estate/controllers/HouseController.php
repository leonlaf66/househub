<?php
namespace module\estate\controllers;

use WS;
use yii\web\Controller;
use \module\cms\helpers\UrlParamEncoder;

class HouseController extends Controller
{
    public function actionIndex($type = 'lease', $tab = 'search', $q='', $params='')
    {
        $stateId = WS::$app->area->stateId;

        $req = WS::$app->request;
        WS::$app->share('rets.property', $type);
        WS::$app->share('rets.tab', $tab);

        if ($type === 'purchase') { // 默认为前三种房源类型，能过cookie做状态切换
            if ($params === '') {
                if (! \WS::$app->request->cookies->getValue('def-sell-type-flag', false)) {
                    if ($tab === 'search') {
                        $q = '';
                        if (isset($_GET['q'])) $q = '?q='.$_GET['q'];
                        return $this->redirect('/house/purchase/pt-sf2mf2cc/'.$q);
                    }
                    return $this->redirect('/house/purchase/'.$tab.'/pt-sf2mf2cc/');
                }
            } elseif ($params !== 'pt-sf2mf2cc') { // 写cookie状态
                \WS::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'def-sell-type-flag',
                    'value' => 1,
                    'expire' => time() + 3600, // 8 小时
                    'domain' => domain()
                ]));
            }
        }

        UrlParamEncoder::setup($params, [
            'q'=>'q',
            'school-district'=>'sd',
            'subway-line'=>'sl',
            'subway-station'=>'ss',
            'property'=>'pt',
            'price'=>'pr',
            'square'=>'sq',
            'beds'=>'bd',
            'baths'=>'ba',
            'parking'=>'pa',
            'agrage'=>'ag',
            'market-days'=>'md',
            'sort'=>'st',
            'page'=>'p'
        ]);

        // 类型
        $search = \module\estate\models\House::search($stateId);
        if ($type === 'lease') {
            $search->query->andFilterWhere(['=', 'prop_type', 'RN']);
        } else {
            $search->query->andFilterWhere(['<>', 'prop_type', 'RN']);
        }

        $q = $req->get('q', '');
        if (! empty($q)) {
            if (is_numeric($q) && strlen($q) === 5) {
                $search->query->andWhere(['zip_code' => $q]);
            } elseif (preg_match('/[a-zA-Z]{0,2}[0-9]{5,10}/', $q)) {
                $search->query->andWhere(['id' => $q]);
            } else {
                $cityName = mb_convert_case($q, MB_CASE_TITLE, 'UTF-8');
                $city = \models\City::findByName(\WS::$app->area->stateId, $cityName);
                if ($city) {
                    $search->query->andWhere('city_id=:city_id or parent_city_id=:city_id', [':city_id' => $city->id]);
                } else {
                    $search->query->where('1=2');
                }
            }
        }

        WS::$app->page->setId('estate/house/'.$type);

        return $this->render('index.phtml', [
            'tab'=>$tab,
            'type'=>$type,
            'search'=>$search
        ]);
    }

    public function actionView($type = 'lease', $id = null)
    {   
        $rets = \common\listhub\estate\House::findOne($id);
        // var_dump($rets->getXmlElement());exit;

        if (is_null($rets )) {
            throw new \yii\web\HttpException(404, "Page not found");
        }
        if (!in_array($rets->state, WS::$app->area->stateIds)) {
            throw new \yii\web\HttpException(404, "Page not found");
        }

        WS::$app->page->setId('estate/house/'.$type.'/view');
        WS::$app->page->bindParams(['name' => $rets->title()]);

        return $this->render("view.phtml", [
            'type'=>$type,
            'rets'=>$rets,
        ]);
    }

    public function actionTest($id) {
        $rets = \common\estate\Rets::findOne($id);
        dd($rets->render()->detail());
    }

    public function actionData($id)
    {
        $rets = \models\listhub\Rets::findOne('2328410', 'ny');
        var_dump($rets->one('xxx/xxx/xxx')->val());exit;
        echo '<pre>';
        var_dump($rets);exit;
    }
}