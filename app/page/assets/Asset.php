<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace module\page\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Asset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'static/lib/tipped-4.6.0-light/css/tipped/tipped.css',
        'static/css/jquery.typeahead.min.css',
        'static/css/main.css'
    ];
    public $js = [
        'static/lib/jquery.min.js',
        'static/lib/tipped-4.6.0-light/js/tipped/tipped.js',
        'static/js/jquery.typeahead.min.js',
        'static/js/app.js'
    ];
    public $depends = [];

    public $jsOptions = ['position'=>\yii\web\View::POS_HEAD];
}
