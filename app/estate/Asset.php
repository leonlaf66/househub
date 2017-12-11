<?php
namespace module\estate;

class Asset extends \yii\web\AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $position = self::HEADE;
    public $css = [
        
    ];
    public $js = [
        
    ];
    public $depends = [
    	'module\assets\AppAsset'
    ];
}