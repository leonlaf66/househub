<?php
namespace module\estate\widgets\search\filter;

use WS;
use yii\helpers\Url;
use module\estate\helpers\SearchUrl;

class SchoolDistrictss extends \yii\base\Widget 
{  
    public $search = null;

    public function run()
    {  
        $search = $this->search;

        if (isset($_GET['school-district'])) {
            $schoolDistricts = explode('|', $_GET['school-district']);
            foreach($schoolDistricts as $i=>$d) {
                $schoolDistricts[$i] = strtoupper($d);
            }
            $search->query->andWhere(['in', 'town', $schoolDistricts]);
        }

        return $this->render('school-districtss.phtml', [
            'self'=>$this,
            'items'=>$this->getItems()
        ]);
    }

    public function isCurrent($value)
    {
        return WS::$app->request->get('school-district', '') === strtolower($value);
    }

    public function createUrl ($val)
    {
        $val = strtolower($val);
        $property = WS::$app->share('rets.property');
        $tab = WS::$app->share('rets.tab');
        if ($tab === 'search') {
            $tab = '';
        } else {
            $tab = '/'.$tab;
        }

        return SearchUrl::to('school-district', $val);
    }

    public function getItems()
    {
        return array_map(function ($item) {
            $code = $item->code;
            if (strpos($code, '/') !== false) {
                $code = str_replace('/', '|', $code);
            }

            $name = $item->name;
            foreach (['联合学区', '学区'] as $xq) {
                if (strpos($name, $xq)) {
                    $name = str_replace($xq, '', $name);
                }
            }

            return [$code, $name];
        }, \models\SchoolDistrict::xFind()->all());
    }
}