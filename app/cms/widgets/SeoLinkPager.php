<?php

namespace module\cms\widgets;

use yii\helpers\Html;

class SeoLinkPager extends \yii\widgets\LinkPager
{
    public $firstPageLabel = true;
    public $lastPageLabel = true;
    public $createUrlFn = null;

    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => $class === '' ? null : $class];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);

            return Html::tag('li', Html::tag('span', $label), $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;

        return Html::tag('li', Html::a($label, ($this->createUrlFn)($page + 1), $linkOptions), $options);
    }
}
