<?php
namespace module\yellowpage\widgets;

use models\TaxonomyTerm;

class TypeSelector extends \yii\base\Widget
{
    public function init()
    {
    	parent::init();
    }

    public function getTreeItems()
    {
        return TaxonomyTerm::getTreeItems(TaxonomyTerm::YELLOW_PAGE);
    }

    public function renderItem($item)
    {
        $item['icon'] = 'icon-test.png';
        if (preg_match('/\[(.*\.png)\]/', $item['name'], $matched)) {
            $item['icon'] = $matched[1];
            $item['name'] = str_replace($matched[0], '', $item['name']);
        }
        return $item;
    }

    public function createTypeUrl($id)
    {
        $args = array_merge($_GET, ['type'=>$id]);
        $args[0] = '/yellowpage/search/result';

        return \WS::$app->urlManager->createUrl($args);
    }

    public function isActive($typeId)
    {
        if(! isset($_GET['type'])) return false;
        $actTypeId = intval($_GET['type']);
        if(! $actTypeId) return false;

        if($actTypeId === $typeId) return true;

        $m = TaxonomyTerm::findOne($actTypeId);
        if(! $m) return false;

        return $m->parent_id === $typeId;
    }

    public function run()
    {
    	return $this->render('type-selector.phtml');
    }
}