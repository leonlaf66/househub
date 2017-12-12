<?php
namespace module\estate\widgets\home;

class Top extends \yii\base\Widget
{
    public function run()
    {
        $items = \models\SiteSetting::get('home.luxury.houses', \WS::$app->area->id);
        
        /*分列 以便更容易渲染输出*/
        $groups = [];
        $groupIndex = 0;
        $colTotal = 0;
        foreach ($items as $item) {
            list($colWidth, $cols) = explode('-', $item['col_rule']);
            $colWidth = intval($colWidth);
            $cols = intval($cols);

            if ($colTotal + $colWidth < $cols) { // 当前组内
                $groups[$groupIndex][] = (array)$item;
                $colTotal += $colWidth;
            } else { // 到达组内最后一个
                $groups[$groupIndex][] = (array)$item; // 实际是前一组的最后一个
                $colTotal = 0;
                $groupIndex ++;
            }
        }

        return $this->render('top.phtml', [
            'groups'=>$groups,
            'imageRoot'=>media_url('rets/home-top')
        ]);
    }
}