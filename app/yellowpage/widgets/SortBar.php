<?php
namespace module\yellowpage\widgets;

use models\YellowPage;

class SortBar extends \yii\base\Widget 
{  
    public function run()
    {  
        $items = Yellowpage::getOrderItems();

        if(isset($_GET['sort'])) {
            $current = $_GET['sort'];
            if(isset($items[$current])) {
                $items[$current]['current'] = true;
                if(isset($_GET['dir']) && in_array($_GET['dir'], array('1', '2'))) {
                    $items[$current]['direction'] = $_GET['dir'];
                }
            }
        }

        return $this->render('sort-bar.phtml', [
            'items'=>$items
        ]);  
    }

    public function getSortUrl($key) {
        $setting = YellowPage::getOrderItems($key);
        if(! $setting) return '#';

        $params = $_GET;
        $params['sort'] = $key;
        $changed = false;
        if(isset($params['dir'])) {
            $dir = $params['dir'];
            if(in_array($dir, array('1', '2'))) {
                if($_GET['sort'] == $key) {
                    $params['dir'] = $dir == '1' ? '2' : '1';
                    $changed = true;
                }
            }
        }

        if(! $changed)
            $params['dir'] = $setting['direction'];

        return \yii\helpers\BaseUrl::current($params);
    }
}