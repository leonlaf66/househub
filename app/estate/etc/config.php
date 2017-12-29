<?php
return [
    'layout'=>'estate.phtml',
    'urlRules'=>[
        /*搜索*/
        'house/<type:(lease|purchase)>/<tab:(subway)>/<params:[A-Za-z0-9-_\s|]+>/'=>'estate/house/index',
        'house/<type:(lease|purchase)>/<tab:(subway)>/'=>'estate/house/index',
        'house/<type:(lease|purchase)>/<params:[A-Za-z0-9-_|\s]+>/'=>'estate/house/index',
        'house/<type:(lease|purchase)>/'=>'estate/house/index',

        /*详情*/
        '<type:(lease|purchase)>/<id:[A-Za-z\d]+>/'=>'estate/house/view',
        /*搜索自动完成*/
        'house/search/autocomplete/'=>'estate/autocomplete/index',

        /*地图*/
        'map/house/<type:(lease|purchase)>'=>'estate/map/index',
        'map/house/<type:(lease|purchase)>/search'=>'estate/map/search',
        'map/house/<id:[A-Z0-9]+>'=>'estate/map/detail',
        /*测试*/
        'house/test/<id:\d+>/'=>'estate/house/test',
        'house/<id:\d+>/data/'=>'estate/house/data'
    ]
];